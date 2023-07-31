<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\PartSchema;
use BitBag\OpenMarketplace\App\Service\PartSaverService;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartSchemaController extends RestAbstractController
{
    #[Route(path: "search/part/schema/{catalogId}/{carId}/{groupId}", name: "search_part", methods: ["GET"])]
    public function search(string $catalogId, string $carId, string $groupId, PartSaverService $partSaverService): Response
    {
        try {
            $partSchema = $this->dm->getRepository(PartSchema::class)->findOneBy(['catalogId' => $catalogId,
                'carId' => $carId, 'groupId' => $groupId]);

            if (empty($partSchema)) {
                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/parts2/?carId=' . $carId . '&groupId=' . $groupId,
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $partData = (object)$responseArray;
                    $partSchema = new PartSchema();
                    $partSchema->setPartSchemaData($partData)
                        ->setCatalogId($catalogId)
                        ->setCarId($carId)
                        ->setDateTime()
                        ->setGroupId($groupId);

                    $this->dm->persist($partSchema);

                    //TODO cron jobs for savings parts.
                    $partSaverService->savePartFromSchema($partSchema, $responseArray, $this->dm);

                    $this->dm->flush();

                } else {
                    throw new \Exception('The Part does not exist');
                }
            }

            return $this->json(['data' => $partSchema->getPartData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
