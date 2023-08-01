<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\DataQuery\AbstractDataQuery;
use BitBag\OpenMarketplace\App\Document\PartCatalogCriteria;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartCatalogCriteriaDataQuery extends AbstractDataQuery
{
    /**
     * @param string $carId
     * @param string $criteria
     * @return PartCatalogCriteria
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $catalogId, string $carId, string $criteria): PartCatalogCriteria
    {
        $partCatalog = $this->dm->getRepository(PartCatalogCriteria::class)->findOneBy(['catalogId' => $catalogId,
            'carId' => $carId, 'criteria' => $criteria]);

        if (empty($partCatalog)) {
            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups2/?carId=' . $carId . '&criteria=' . $criteria,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $catalogData = (object)$responseArray;
                $partCatalog = new PartCatalogCriteria();
                $partCatalog->setCatalogData($catalogData)
                    ->setCatalogId($catalogId)
                    ->setCriteria($criteria)
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
