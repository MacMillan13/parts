<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartCatalog;
use BitBag\OpenMarketplace\App\Service\NamingService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PartCatalogDataQuery extends AbstractDataQuery
{
    public function __construct(HttpClientInterface $client, DocumentManager $dm,
                                private NamingService $namingService)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @return PartCatalog
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function query(string $catalogId, string $carId): PartCatalog
    {
        $partCatalog = $this->dm->getRepository(PartCatalog::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $carId]);

        if (empty($partCatalog)) {

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups2/?carId=' . $carId,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {

                foreach ($responseArray as &$data) {
                    $data['code'] = $this->namingService->prepare($data['name']);
                }

                $catalogData = (object)$responseArray;
                $partCatalog = new PartCatalog();
                $partCatalog->setCatalogData($catalogData)
                    ->setCatalogId($catalogId)
                    ->setDateTime()
                    ->setCarId($carId);

                $this->dm->persist($partCatalog);
                $this->dm->flush();
            } else {
                throw new Exception('The data does not exist');
            }
        }

        return $partCatalog;
    }
}
