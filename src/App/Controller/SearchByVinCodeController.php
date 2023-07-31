<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\Auto;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class SearchByVinCodeController extends RestAbstractController
{
    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    #[Route(path: "search/vin/{vinCode}", name: "search_by_vin", methods: ["GET"])]
    public function searchByVinCode(string $vinCode): Response
    {
        try {
            $auto = $this->dm->getRepository(Auto::class)->findOneBy(['vinCode' => $vinCode]);

            if (empty($auto)) {

                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'car/info?q=' . $vinCode,
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $autoData = (object)$responseArray[0];
                    $auto = new Auto();
                    $auto->setAutoData($autoData)
                        ->setVinCode($vinCode);

                    $this->dm->persist($auto);
                    $this->dm->flush();
                } else {
                    throw new \Exception('The Vin Code does not exist');
                }
            }

            return $this->json(['data' => $auto->getAutoData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }
}
