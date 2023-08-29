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

    public function __construct(private AutoModelDataQuery $carModelDataQuery, private AutoCatalogDataQuery $carCatalogDataQuery,
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

        $carCatalog = $this->getCatalogWithTheRightModel($carModel, $brand);

        //TODO years have the same id everywhere, we can optimize this request by adding to previous.
        $carCatalog = $this->setYear($carCatalog, (array)$carCatalog->getParameters(), $carData);

        $carCatalog = $this->carCatalogDataQuery->query($carCatalog);

        $carCatalog = $this->setValues($carCatalog, (array)$carCatalog->getParameters());

        $carCatalog = $this->carCatalogDataQuery->query($carCatalog);

        $this->saveCarVin($carVin, $carCatalog, $carData['vin']);

        return $carCatalog;
    }

    /**
     * @param AutoCatalog $carCatalog
     * @param array $parameters
     * @param array $carData
     * @return AutoCatalog
     */
    private function setYear(AutoCatalog $carCatalog, array $parameters, array $carData): AutoCatalog
    {
        foreach ($parameters as $parameter) {
            if ($parameter['key'] == 'year') {
                foreach ($parameter['values'] as $item) {
                    if ($carData['year'] === $item['value']) {
                        $carCatalog->setYearId($item['idx']);
                    }
                }
            }
        }

        return $carCatalog;
    }

    /**
     * @param AutoCatalog $carCatalog
     * @param array $parameters
     * @return AutoCatalog
     */
    private function setValues(AutoCatalog $carCatalog, array $parameters): AutoCatalog
    {
        foreach ($parameters as $parameter) {
            switch ($parameter['key']) {
                case 'sales_region':
                    foreach ($parameter['values'] as $item) {
                        if (self::DEFAULT_REGION === $item['value']) {
                            $carCatalog->setRegionId($item['idx']);
                        }
                    }
                    break;
                case 'steering':
                    foreach ($parameter['values'] as $item) {
                        if (self::DEFAULT_STEERING === $item['value']) {
                            $carCatalog->setSteeringId($item['idx']);
                        }
                    }
                    break;
            }
        }

        return $carCatalog;
    }

    /**
     * @param AutoVin $carVin
     * @param AutoCatalog $carCatalog
     * @param string $vinCode
     * @return void
     * @throws MongoDBException
     * @throws \MongoException
     */
    private function saveCarVin(AutoVin $carVin, AutoCatalog $carCatalog, string $vinCode): void
    {
        $carVin->setCatalogId(new MongoId($carCatalog->getId()))
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
        $carCatalog = new AutoCatalog();
        $carCatalog->setModelId($carModel['id']);
        $carCatalog->setCatalogId($brand);

        return $this->carCatalogDataQuery->query($carCatalog);
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
