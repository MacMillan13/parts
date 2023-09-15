<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartCatalogGroup;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartCatalogGroupDataQuery extends AbstractDataQuery
{
    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $groupId
     * @return PartCatalogGroup
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $catalogId, string $carId, string $groupId, string $groupName): PartCatalogGroup
    {
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
                ->setGroup($groupName)
                ->setGroupId($groupId);

            $this->dm->persist($partCatalogGroup);
            $this->dm->flush();
        } else {
            throw new Exception('The Part Catalog Group does not exist');
        }

        return $partCatalogGroup;
    }
}
