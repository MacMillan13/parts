<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\DataQuery\AbstractDataQuery;
use BitBag\OpenMarketplace\App\Document\PartCatalog;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartCatalogDataQuery extends AbstractDataQuery
{
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
