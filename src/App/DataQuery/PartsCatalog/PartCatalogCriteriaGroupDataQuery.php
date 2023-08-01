<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartCatalogCriteriaGroup;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartCatalogCriteriaGroupDataQuery extends AbstractDataQuery
{
    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $criteria
     * @param string $groupId
     * @return PartCatalogCriteriaGroup
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $catalogId, string $carId, string $criteria, string $groupId): PartCatalogCriteriaGroup
    {
        $partCatalogGroup = $this->dm->getRepository(PartCatalogCriteriaGroup::class)
            ->findOneBy(['catalogId' => $catalogId, 'carId' => $carId, 'groupId' => $groupId]);

        if (empty($partCatalogGroup)) {

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups2/?carId=' . $carId . '&criteria=' . $criteria . '&groupId=' . $groupId,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $catalogData = (object)$responseArray;
                $partCatalogGroup = new PartCatalogCriteriaGroup();
                $partCatalogGroup->setCatalogData($catalogData)
                    ->setCatalogId($catalogId)
                    ->setCriteria($criteria)
                    ->setCarId($carId)
                    ->setDateTime()
                    ->setGroupId($groupId);

                $this->dm->persist($partCatalogGroup);
                $this->dm->flush();
            } else {
                throw new Exception('The Part Catalog Group does not exist');
            }
        }

        return $partCatalogGroup;
    }
}
