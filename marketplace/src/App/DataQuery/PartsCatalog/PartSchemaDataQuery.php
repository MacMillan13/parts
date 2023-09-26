<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartSchema;
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
    public function __construct(private PartSaverService $partSaverService, HttpClientInterface $client, DocumentManager $dm)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param string $catalogId
     * @param string $carId
     * @param string $groupId
     * @return PartSchema
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \MongoException
     */
    public function query(string $catalogId, string $carId, string $groupId): PartSchema
    {
        $partSchema = $this->dm->getRepository(PartSchema::class)->findOneBy(['catalogId' => $catalogId,
            'carId' => $carId, 'groupId' => $groupId]);

        if (empty($partSchema)) {
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
                    ->setGroup()
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
