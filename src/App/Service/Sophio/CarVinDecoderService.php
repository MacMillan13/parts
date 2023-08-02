<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service\Sophio;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\CarCatalogDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\CarModelDataQuery;
use BitBag\OpenMarketplace\App\Document\CarCatalog;
use BitBag\OpenMarketplace\App\Document\CarModel;
use BitBag\OpenMarketplace\App\Document\CarVin;
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

    public function __construct(private CarModelDataQuery $carModelDataQuery, private CarCatalogDataQuery $carCatalogDataQuery,
        private DocumentManager $documentManager)
    {
    }

    /**
     * @param array $carData
     * @return CarCatalog
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function decoder(CarVin $carVin, array $carData): CarCatalog
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
     * @param CarCatalog $carCatalog
     * @param array $parameters
     * @param array $carData
     * @return CarCatalog
     */
    private function setYear(CarCatalog $carCatalog, array $parameters, array $carData): CarCatalog
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
     * @param CarCatalog $carCatalog
     * @param array $parameters
     * @return CarCatalog
     */
    private function setValues(CarCatalog $carCatalog, array $parameters): CarCatalog
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
     * @param CarVin $carVin
     * @param CarCatalog $carCatalog
     * @param string $vinCode
     * @return void
     * @throws MongoDBException
     * @throws \MongoException
     */
    private function saveCarVin(CarVin $carVin, CarCatalog $carCatalog, string $vinCode): void
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
     * @return CarCatalog
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getCatalogWithTheRightModel(array $carModel, string $brand): CarCatalog
    {
        $carCatalog = new CarCatalog();
        $carCatalog->setModelId($carModel['id']);
        $carCatalog->setCatalogId($brand);

        return $this->carCatalogDataQuery->query($carCatalog);
    }

    /**
     * @param CarModel $carModelList
     * @param string $modelFromResponse
     * @return array
     * @throws Exception
     */
    private function getCarModer(CarModel $carModelList, string $modelFromResponse): array
    {
        foreach ($carModelList->getModels() as $model) {
            if (strtolower($model['name']) === strtolower($modelFromResponse)) {
                return $model;
            }
        }

        throw new Exception('Model does not exist.');
    }
}
