<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartCatalogGroup;
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

class PartCatalogGroupDataQuery extends AbstractDataQuery
{

    public function __construct(HttpClientInterface $client, DocumentManager $dm,
                                private NamingService $namingService)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $groupId
     * @param string $groupName
     * @return PartCatalogGroup
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function query(string $catalogId, string $carId, string $groupId, string $groupName): PartCatalogGroup
    {
        $partCatalogGroup = $this->dm->getRepository(PartCatalogGroup::class)->findOneBy(['catalogId' => $catalogId,
            'carId' => $carId, 'groupId' => $groupId, 'group' => $groupName]);

        if (empty($partCatalogGroup)) {
            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups2/?carId=' . $carId . '&groupId=' . $groupId,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {

                foreach ($responseArray as &$data) {
                    $data['code'] = $this->namingService->prepare($data['name']);
                }

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
        }

        return $partCatalogGroup;
    }
}
