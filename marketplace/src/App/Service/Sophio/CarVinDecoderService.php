<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service\Sophio;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoCatalogDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoModelDataQuery;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Document\AutoModel;
use BitBag\OpenMarketplace\App\Document\AutoVin;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use MongoId;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CarVinDecoderService
{
    const DEFAULT_REGION = 'USA';
    const DEFAULT_STEERING = 'Left hand';

    public function __construct(private AutoModelDataQuery $carModelDataQuery, private AutoCatalogDataQuery $autoCatalogDataQuery,
                                private DocumentManager    $documentManager)
    {
    }

    /**
     * @param array $carData
     * @return AutoCatalog
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function decoder(AutoVin $carVin, array $carData): AutoCatalog
    {
        $brand = strtolower($carData['make']);

        $carModelList = $this->carModelDataQuery->query($brand);

        $carModel = $this->getCarModer($carModelList, $carData['model']);

        $autoCatalog = $this->getCatalogWithTheRightModel($carModel, $brand);

        //TODO years have the same id everywhere, we can optimize this request by adding to previous.
        $autoCatalog = $this->setYear($autoCatalog, (array)$autoCatalog->getParameters(), $carData);

        $autoCatalog = $this->autoCatalogDataQuery->query($autoCatalog);

        $autoCatalog = $this->setValues($autoCatalog, (array)$autoCatalog->getParameters());

        $autoCatalog = $this->autoCatalogDataQuery->query($autoCatalog);

        $this->saveCarVin($carVin, $autoCatalog, $carData['vin']);

        return $autoCatalog;
    }

    /**
     * @param AutoCatalog $autoCatalog
     * @param array $parameters
     * @param array $carData
     * @return AutoCatalog
     */
    private function setYear(AutoCatalog $autoCatalog, array $parameters, array $carData): AutoCatalog
    {
        foreach ($parameters as $parameter) {
            if ($parameter['key'] == 'year') {
                foreach ($parameter['values'] as $item) {
                    if ($carData['year'] === $item['value']) {
                        $autoCatalog->setYearId($item['idx']);
                    }
                }
            }
        }

        return $autoCatalog;
    }

    /**
     * @param AutoCatalog $autoCatalog
     * @param array $parameters
     * @return AutoCatalog
     */
    private function setValues(AutoCatalog $autoCatalog, array $parameters): AutoCatalog
    {
        foreach ($parameters as $parameter) {
            switch ($parameter['key']) {
                case 'sales_region':
                    foreach ($parameter['values'] as $item) {
                        if (self::DEFAULT_REGION === $item['value']) {
                            $autoCatalog->setRegionId($item['idx']);
                        }
                    }
                    break;
                case 'steering':
                    foreach ($parameter['values'] as $item) {
                        if (self::DEFAULT_STEERING === $item['value']) {
                            $autoCatalog->setSteeringId($item['idx']);
                        }
                    }
                    break;
            }
        }

        return $autoCatalog;
    }

    /**
     * @param AutoVin $carVin
     * @param AutoCatalog $autoCatalog
     * @param string $vinCode
     * @return void
     * @throws MongoDBException
     * @throws \MongoException
     */
    private function saveCarVin(AutoVin $carVin, AutoCatalog $autoCatalog, string $vinCode): void
    {
        $carVin->setCatalogId($autoCatalog->getId())
            ->setVinCode($vinCode)
            ->setExactMatch(false)
            ->setDateTime();

        $this->documentManager->persist($carVin);
        $this->documentManager->flush();

        return;
    }

    /**
     * @param array $carModel
     * @param string $brand
     * @return AutoCatalog
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getCatalogWithTheRightModel(array $carModel, string $brand): AutoCatalog
    {
        $autoCatalog = new AutoCatalog();
        $autoCatalog->setModelId($carModel['id']);
        $autoCatalog->setCatalogId($brand);

        return $this->autoCatalogDataQuery->query($autoCatalog);
    }

    /**
     * @param AutoModel $carModelList
     * @param string $modelFromResponse
     * @return array
     * @throws Exception
     */
    private function getCarModer(AutoModel $carModelList, string $modelFromResponse): array
    {
        foreach ($carModelList->getModels() as $model) {
            if (strtolower($model['name']) === strtolower($modelFromResponse)) {
                return $model;
            }
        }

        throw new Exception('Model does not exist.');
    }
}
