<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\AutoVin;
use BitBag\OpenMarketplace\App\Document\PartCatalogCriteriaGroup;
use BitBag\OpenMarketplace\App\Helper\ElementCodeHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PartCatalogCriteriaGroupDataQuery extends AbstractDataQuery
{

    public function __construct(HttpClientInterface       $client, DocumentManager $dm,
                                private ElementCodeHelper $elementCodeHelper,
                                private PartCatalogCriteriaDataQuery $partCatalogCriteriaDataQuery)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param AutoVin $auto
     * @param string $group
     * @return PartCatalogCriteriaGroup
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(AutoVin $auto, string $group, string $subgroup = null): PartCatalogCriteriaGroup
    {
        if (!empty($subgroup)) {
            $searchGroup = $subgroup;
        } else {
            $searchGroup = $group;
        }
        $partCatalogCriteriaGroup = $this->dm->getRepository(PartCatalogCriteriaGroup::class)
            ->findOneBy(['carId' => $auto->getAutoData()->carId, 'group' => $searchGroup]);

        if (empty($partCatalogCriteriaGroup)) {
            if ($auto->getExactMatch()) {
                $autoData = $auto->getAutoData();
                $catalogId = $autoData->catalogId;
                $carId = $autoData->carId;
                $criteria = $autoData->criteria;
                $partCatalogCriteriaData = $this->partCatalogCriteriaDataQuery->query($catalogId, $carId, $criteria);
                $catalogData = $partCatalogCriteriaData->getCatalogData();

                foreach ($catalogData as $catalogGroup) {
                    if ($catalogGroup['code'] === $group) {
                        $partCatalogCriteriaGroup = $this->request($auto, $catalogGroup);
                        break;
                    }
                }

                if (!empty($subgroup)) {
                    foreach ($partCatalogCriteriaGroup->getCatalogData() as $catalogGroup) {
                        if ($catalogGroup['code'] === $subgroup) {
                            $partCatalogCriteriaSubGroup = $this->request($auto, $catalogGroup);
                            break;
                        }
                    }

                    if (!empty($partCatalogCriteriaSubGroup)) {
                        $partCatalogCriteriaGroup = $partCatalogCriteriaSubGroup;
                    } else {
                        throw new \Exception('Error, no part sub group data');
                    }
                }
            }
        }

        return $partCatalogCriteriaGroup;
    }

    /**
     * @param AutoVin $auto
     * @param array $group
     * @return PartCatalogCriteriaGroup
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function request(AutoVin $auto, array $group): PartCatalogCriteriaGroup
    {
        $response = $this->client->request(
            'GET',
            $_ENV['PART_CATALOG_API'] . 'catalogs/' . $auto->getAutoData()->catalogId . '/groups2/?carId='
            . $auto->getAutoData()->carId . '&criteria=' . $auto->getAutoData()->criteria . '&groupId=' . $group['id'],
            $this->getHeaders()
        );

        if (!empty($responseArray = $response->toArray())) {

            foreach ($responseArray as &$data) {
                $data['code'] = $this->elementCodeHelper->prepare($data['name']);
            }

            $catalogData = (object)$responseArray;
            $partCatalogGroup = new PartCatalogCriteriaGroup();
            $partCatalogGroup->setCatalogData($catalogData)
                ->setCatalogId($auto->getAutoData()->catalogId)
                ->setCriteria($auto->getAutoData()->criteria)
                ->setCarId($auto->getAutoData()->carId)
                ->setDateTime()
                ->setGroup($group['code'])
                ->setGroupId($group['id']);

            $this->dm->persist($partCatalogGroup);
            $this->dm->flush();
        } else {
            throw new Exception('The Part Catalog Group does not exist');
        }

        return $partCatalogGroup;
    }
}
