<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartSchema;
use BitBag\OpenMarketplace\App\Helper\ElementCodeHelper;
use BitBag\OpenMarketplace\App\Service\PartSaverService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PartSchemaDataQuery extends AbstractDataQuery
{
    /**
     * @param PartSaverService $partSaverService
     * @param HttpClientInterface $client
     * @param DocumentManager $dm
     */
    public function __construct(HttpClientInterface $client, DocumentManager $dm, private PartSaverService $partSaverService,
        private PartCatalogDataQuery                $partCatalogDataQuery, private PartCatalogGroupDataQuery $partCatalogGroupDataQuery, private ElementCodeHelper $elementCodeHelper)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $groupName
     * @param string $schemaName
     * @return PartSchema
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \MongoException
     * @throws Exception
     */
    public function query(string $catalogId, string $carId, string $groupName, string $schemaName): PartSchema
    {
        $partSchema = $this->dm->getRepository(PartSchema::class)->findOneBy(['catalogId' => $catalogId,
            'carId' => $carId, 'group' => $groupName, 'code' => $schemaName]);

        if (empty($partSchema)) {

            $partCatalog = $this->partCatalogDataQuery->query($catalogId, $carId);

            foreach ($partCatalog->getCatalogData() as $partCatalog) {

                if ($partCatalog['code'] === $groupName) {
                    $partCatalogGroup = $this->partCatalogGroupDataQuery->query($catalogId, $carId, $partCatalog['id'], $groupName);
                    break;
                }
            }

            if (empty($partCatalogGroup)) {
                throw new \Exception('Error, no part group data');
            }

            $groupId = null;

            foreach ($partCatalogGroup->getCatalogData() as $subGroups) {
                if ($schemaName === $subGroups['code']) {
                    $groupId = $subGroups['id'];
                }
            }

            if (empty($groupId)) {
                throw new \Exception('Error, no schema id');
            }

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/parts2/?carId=' . $carId . '&groupId=' . $groupId,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {

                $partData = (object)$responseArray;
                $partSchema = new PartSchema();
                $partSchema->setPartSchemaData($partData)
                    ->setCatalogId($catalogId)
                    ->setCarId($carId)
                    ->setDateTime()
                    ->setCode($schemaName)
                    ->setGroup($groupName)
                    ->setGroupId($groupId);

                $this->dm->persist($partSchema);

                //TODO cron jobs or queue for savings parts.
                $this->partSaverService->savePartFromSchema($partSchema, $responseArray);

                $this->dm->flush();

            } else {
                throw new Exception('The Part does not exist');
            }
        }

        return $partSchema;
    }
}
