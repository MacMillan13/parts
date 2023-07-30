<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\Auto;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route(path: "/api/v3/")]
class SearchByVinCodeController extends RestAbstractController
{
    public function __construct(private HttpClientInterface $client, private DocumentManager $dm)
    {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Doctrine\ODM\MongoDB\MongoDBException
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     */
    #[Route(path: "search-by-vin/{vinCode}", name: "search_by_vin", methods: ["GET"])]
    public function searchByVinCode($vinCode): Response
    {
        try {
            $auto = $this->dm->getRepository(Auto::class)->findOneBy(['key' => $vinCode]);

            if (empty($auto)) {

                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'car/info?q=' . $vinCode,
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $object = (object)$responseArray[0];
                    $newAuto = new Auto();
                    $newAuto->setAutoData($object);
                    $newAuto->setKey($vinCode);

                    $this->dm->persist($newAuto);
                    $this->dm->flush();
                }
            }

            return $this->json(['data' => $auto->getAutoData()], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }

    }
}
