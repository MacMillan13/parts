<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoCatalogDataQuery;
use BitBag\OpenMarketplace\App\Document\Auto;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class AutoService
{
    /**
     * @param DocumentManager $documentManager
     * @param AutoModelService $autoModelService
     * @param AutoCatalogDataQuery $autoCatalogDataQuery
     */
    public function __construct(private DocumentManager $documentManager, private AutoModelService $autoModelService,
        private AutoCatalogDataQuery $autoCatalogDataQuery)
    {
    }

    /**
     * @param string $catalogId
     * @param string $modelName
     * @param string $year
     * @param string $modification
     * @return Auto
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    public function getAutoByModification(string $catalogId, string $modelName, string $year, string $modification): Auto
    {
        $autoRep = $this->documentManager->getRepository(Auto::class);

        $auto = $autoRep->findOneBy(['catalogId' => $catalogId, 'modelName' => $modelName, 'year' => $year, 'code' => $modification]);

        if (empty($auto)) {

            $modelId = $this->autoModelService->getAutoModelId($catalogId, $modelName);

            $autoCatalog = new AutoCatalog();
            $autoCatalog->setCatalogId($catalogId);
            $autoCatalog->setModelId($modelId);

            $autoCatalog = $this->autoCatalogDataQuery->query($autoCatalog);
            $autoList = $autoCatalog->getCarList();

            foreach ($autoList as $oneAuto) {
                if (!empty($oneAuto['code']) && $oneAuto['code'] === $modification) {
                    $auto = $oneAuto;
                }
            }
        }

        if (empty($auto)) {
            throw new \Exception('Cannot find an auto.');
        }

        $autoEntity = new Auto();

        $autoEntity->setModelId($auto['modelId'])
            ->setCatalogId($auto['catalogId'])
            ->setCode($auto['code'])
            ->setParameters($auto['parameters'])
            ->setModelName($auto['modelName'])
            ->setForeignId($auto['modelId'])
            ->setDescription($auto['description'])
            ->setFrame($auto['frame'])
            ->setCriteria($auto['criteria'])
            ->setBrand($auto['brand'])
            ->setName($auto['name'])
            ->setYear($year)
            ->setVin($auto['vin']);

        return $autoEntity;
    }
}
