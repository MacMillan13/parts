<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\CarCatalog;
use BitBag\OpenMarketplace\App\Helper\CarCatalogUrlHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CarCatalogDataQuery extends AbstractDataQuery
{
    /**
     * @param HttpClientInterface $client
     * @param DocumentManager $dm
     * @param CarCatalogUrlHelper $catalogParametersUrlHelper
     */
    public function __construct(HttpClientInterface $client, DocumentManager $dm,
                                private CarCatalogUrlHelper $catalogParametersUrlHelper)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param CarCatalog $carCatalog
     * @return CarCatalog
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(CarCatalog $carCatalog): CarCatalog
    {
        $searchParams = [];

        $searchParams['catalogId'] = $carCatalog->getCatalogId();

        $searchParams['modelId'] = $carCatalog->getModelId();

        $searchParams['yearId'] = $carCatalog->getYearId();

        $searchParams['regionId'] = $carCatalog->getRegionId();

        $searchParams['steeringId'] = $carCatalog->getSteeringId();

        $searchParams['seriesId'] = $carCatalog->getSeriesId();

        $searchParams['bodyTypeId'] = $carCatalog->getBodyTypeId();

        $searchParams['transmissionTypeId'] = $carCatalog->getTransmissionTypeId();

        $searchParams['exactModelId'] = $carCatalog->getExactModelId();

        $searchParams['engineId'] = $carCatalog->getEngineId();

        $searchParams['sunroof'] = $carCatalog->getSunroof();

        $searchParams['navigation'] = $carCatalog->getNavigation();

        $searchParams['vsa'] = $carCatalog->getVsa();

        $searchParams['doorCount'] = $carCatalog->getDoorCount();

        $searchParams['abs'] = $carCatalog->getAbs();

        $searchParams['modification'] = $carCatalog->getModification();

        $searchParams['productPeriod'] = $carCatalog->getProductPeriod();

        $searchParams['engineCapacity'] = $carCatalog->getEngineCapacity();

        $searchParams['specModelDate'] = $carCatalog->getSpecModelDate();

        $searchParams['specVinPart'] = $carCatalog->getSpecVinPart();

        $searchParams['specModification'] = $carCatalog->getSpecModification();

        $searchParams['specCatalog'] = $carCatalog->getSpecCatalog();

        $searchParams['carName'] = $carCatalog->getCarName();

        $searchParams['specSeries'] = $carCatalog->getSpecSeries();

        $searchParams['fuelType'] = $carCatalog->getFuelType();

        $searchParams['bodyCode'] = $carCatalog->getBodyCode();

        $searchParams['transmission'] = $carCatalog->getTransmission();

        $searchParams['grade'] = $carCatalog->getGrade();

        $searchParams['classification'] = $carCatalog->getClassification();

        $searchParams['autoParameters'] = $carCatalog->getAutoParameters();

        $carCatalogSearch = $this->dm->getRepository(CarCatalog::class)
            ->findOneBy($searchParams);

        if (empty($carCatalogSearch)) {

            $parametersCriteria = $this->catalogParametersUrlHelper->buildParametersUrl([$carCatalog->getYearId(),
                $carCatalog->getRegionId(), $carCatalog->getSteeringId(), $carCatalog->getSeriesId(), $carCatalog->getBodyTypeId(),
                $carCatalog->getTransmissionTypeId(), $carCatalog->getExactModelId(), $carCatalog->getEngineId(), $carCatalog->getSunroof(),
                $carCatalog->getNavigation(), $carCatalog->getVsa(), $carCatalog->getDoorCount(), $carCatalog->getAbs(),
                $carCatalog->getModification(), $carCatalog->getProductPeriod(), $carCatalog->getEngineCapacity(), $carCatalog->getSpecModelDate(),
                $carCatalog->getSpecVinPart(), $carCatalog->getSpecModification(), $carCatalog->getSpecCatalog(), $carCatalog->getCarName(),
                $carCatalog->getSpecSeries(), $carCatalog->getFuelType(), $carCatalog->getBodyCode(), $carCatalog->getTransmission(),
                $carCatalog->getGrade(), $carCatalog->getClassification(), $carCatalog->getAutoParameters()]);

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $carCatalog->getCatalogId() . '/cars-parameters/?modelId=' . $carCatalog->getModelId() . $parametersCriteria,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $parameters = (object)$responseArray;

                $carCatalog->setParameters($parameters)
                    ->setDateTime();

                $carListResponse = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $carCatalog->getCatalogId() . '/cars2/?modelId=' . $carCatalog->getModelId() . $parametersCriteria,
                    $this->getHeaders()
                );

                if (!empty($carListArray = $carListResponse->toArray())) {
                    $carList = (object)$carListArray;
                    $carCatalog->setCarList($carList);

                    //TODO cron jobs or queue for savings cars.
                }

                $notIdentifiedCarCatalog = clone $carCatalog;

                $this->dm->persist($notIdentifiedCarCatalog);

                $this->dm->flush();
            }

            return $carCatalog;

        } else {

            return $carCatalogSearch;
        }
    }
}
