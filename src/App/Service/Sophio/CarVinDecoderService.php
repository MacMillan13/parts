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
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CarVinDecoderService
{
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
    public function decoder(array $carData): CarCatalog
    {
        $brand = strtolower($carData['make']);

        $carModelList = $this->carModelDataQuery->query($brand);

        $carModel = $this->getCarModer($carModelList, $carData['model']);

        $carCatalog = $this->getCatalogWithTheRightModel($carModel, $brand);

        $carCatalog = $this->getCatalogWithTheRightYear($carCatalog, $carData);

        $this->saveCarVin($carCatalog, $carData['vin']);

        return $carCatalog;
    }

    /**
     * @param CarCatalog $carCatalog
     * @param string $vinCode
     * @return void
     * @throws MongoDBException
     * @throws \MongoException
     */
    private function saveCarVin(CarCatalog $carCatalog, string $vinCode): void
    {
        $carVin = new CarVin();
        $carVin->setCatalogId(new \MongoId($carCatalog->getId()))
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
     * @param CarCatalog $carCatalog
     * @param array $carData
     * @return CarCatalog
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function getCatalogWithTheRightYear(CarCatalog $carCatalog, array $carData): CarCatalog
    {
        $carCatalog = $this->setTheRightYear($carCatalog, $carData);

        return $this->carCatalogDataQuery->query($carCatalog);
    }

    /**
     * @param CarCatalog $carCatalog
     * @param array $carData
     * @return CarCatalog
     */
    private function setTheRightYear(CarCatalog $carCatalog, array $carData): CarCatalog
    {
        $parameters = (array)$carCatalog->getParameters();

        foreach ($parameters as $parameter) {
            if ('year' === $parameter['key']){
                foreach ($parameter['values'] as $item) {
                    if ($carData['year'] === $item['value']) {
                        $carCatalog->setYearId($item['idx']);
                        break;
                    }
                }
            }
        }

        return $carCatalog;
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
