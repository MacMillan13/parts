<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service\Sophio;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\CarModelDataQuery;
use BitBag\OpenMarketplace\App\Document\CarCatalog;

class CarVinDecoderService
{
    public function __construct(private CarModelDataQuery $carModelDataQuery)
    {

    }
    public function decoder($carData)
    {

        $brand = strtolower($carData['make']);

        $carModelList = $this->carModelDataQuery->getCarModelList($brand);

        foreach ($carModelList->getModels() as $model) {
            if (strtolower($model['name']) === strtolower($carData['model'])) {
                $carModel = $model;
                break;
            }
        }

        if (empty($carModel)) {
            throw new \Exception('Model does not exist.');
        }

        $carCatalog = new CarCatalog();
        $carCatalog->setModelId($carModel['id']);
        $carCatalog->setCatalogId($brand);

        $carCatalog = $this->carCatalogDataQuery->getCatalogData($carCatalog);

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

        return $this->carCatalogDataQuery->getCatalogData($carCatalog);
    }
}
