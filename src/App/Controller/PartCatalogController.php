<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\PartCatalog;
use BitBag\OpenMarketplace\App\Document\PartCatalogGroup;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartCatalogController extends RestAbstractController
{
    #[Route(path: "part/catalog/{catalogId}/{carId}", name: "get_common_part_catalog", methods: ["GET"])]
    public function getPartCatalog(string $catalogId, string $carId): Response
    {
        try {
            $partCatalog = $this->dm->getRepository(PartCatalog::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $carId]);

            if (empty($partCatalog)) {

                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups2/?carId=' . $carId,
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $catalogData = (object)$responseArray;
                    $partCatalog = new PartCatalog();
                    $partCatalog->setCatalogData($catalogData)
                        ->setCatalogId($catalogId)
                        ->setDateTime()
                        ->setCarId($carId);

                    $this->dm->persist($partCatalog);
                    $this->dm->flush();
                } else {
                    throw new \Exception('The Part Catalog does not exist');
                }
            }

            return $this->json(['data' => $partCatalog->getCatalogData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/catalog/{catalogId}/{carId}/{groupId}", name: "get_common_part_catalog_group", methods: ["GET"])]
    public function getPartCatalogGroup(string $catalogId, string $carId, string $groupId): Response
    {
        try {
            $partCatalogGroup = $this->dm->getRepository(PartCatalogGroup::class)->findOneBy(['catalogId' => $catalogId,
                'carId' => $carId, 'groupId' => $groupId]);

            if (empty($partCatalogGroup)) {

                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups2/?carId=' . $carId . '&groupId=' . $groupId,
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $catalogData = (object)$responseArray;
                    $partCatalogGroup = new PartCatalogGroup();
                    $partCatalogGroup->setCatalogData($catalogData)
                        ->setCatalogId($catalogId)
                        ->setCarId($carId)
                        ->setDateTime()
                        ->setGroupId($groupId);

                    $this->dm->persist($partCatalogGroup);
                    $this->dm->flush();
                } else {
                    throw new \Exception('The Part Catalog Group does not exist');
                }
            }

            return $this->json(['data' => $partCatalogGroup->getCatalogData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
