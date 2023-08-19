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

        if (!empty($catalogId = $carCatalog->getCatalogId())) {
            $searchParams['catalogId'] = $catalogId;
        }

        if (!empty($modelId = $carCatalog->getModelId())) {
            $searchParams['modelId'] = $modelId;
        }

        if (!empty($yearId = $carCatalog->getYearId())) {
            $searchParams['yearId'] = $yearId;
        }

        if (!empty($regionId = $carCatalog->getRegionId())) {
            $searchParams['regionId'] = $regionId;
        }

        if (!empty($steeringId = $carCatalog->getSteeringId())) {
            $searchParams['steeringId'] = $steeringId;
        }

        if (!empty($seriesId = $carCatalog->getSeriesId())) {
            $searchParams['seriesId'] = $seriesId;
        }

        if (!empty($bodyTypeId = $carCatalog->getBodyTypeId())) {
            $searchParams['bodyTypeId'] = $bodyTypeId;
        }

        if (!empty($transmissionId = $carCatalog->getTransmissionTypeId())) {
            $searchParams['transmissionTypeId'] = $transmissionId;
        }

        if (!empty($exactModelId = $carCatalog->getExactModelId())) {
            $searchParams['exactModelId'] = $exactModelId;
        }

        if (!empty($engineId = $carCatalog->getEngineId())) {
            $searchParams['engineId'] = $engineId;
        }

        if (!empty($sunroof = $carCatalog->getSunroof())) {
            $searchParams['sunroof'] = $sunroof;
        }

        if (!empty($navigation = $carCatalog->getNavigation())) {
            $searchParams['navigation'] = $navigation;
        }

        if (!empty($vsa = $carCatalog->getVsa())) {
            $searchParams['vsa'] = $vsa;
        }

        if (!empty($doorCount = $carCatalog->getDoorCount())) {
            $searchParams['doorCount'] = $doorCount;
        }

        if (!empty($abs = $carCatalog->getAbs())) {
            $searchParams['abs'] = $abs;
        }

        if (!empty($modification = $carCatalog->getModification())) {
            $searchParams['modification'] = $modification;
        }

        if (!empty($productPeriod = $carCatalog->getProductPeriod())) {
            $searchParams['productPeriod'] = $productPeriod;
        }

        if (!empty($engineCapacity = $carCatalog->getEngineCapacity())) {
            $searchParams['engineCapacity'] = $engineCapacity;
        }

        if (!empty($specModelDate = $carCatalog->getSpecModelDate())) {
            $searchParams['specModelDate'] = $specModelDate;
        }

        if (!empty($specVinPart = $carCatalog->getSpecVinPart())) {
            $searchParams['specVinPart'] = $specVinPart;
        }

        if (!empty($specModification = $carCatalog->getSpecModification())) {
            $searchParams['specModification'] = $specModification;
        }

        if (!empty($specCatalog = $carCatalog->getSpecCatalog())) {
            $searchParams['specCatalog'] = $specCatalog;
        }

        if (!empty($carName = $carCatalog->getCarName())) {
            $searchParams['carName'] = $carName;
        }

        if (!empty($specSeries = $carCatalog->getSpecSeries())) {
            $searchParams['specSeries'] = $specSeries;
        }

        if (!empty($fuelType = $carCatalog->getFuelType())) {
            $searchParams['fuelType'] = $fuelType;
        }

        if (!empty($bodyCode = $carCatalog->getBodyCode())) {
            $searchParams['bodyCode'] = $bodyCode;
        }

        if (!empty($transmission = $carCatalog->getTransmission())) {
            $searchParams['transmission'] = $transmission;
        }

        if (!empty($grade = $carCatalog->getGrade())) {
            $searchParams['grade'] = $grade;
        }

        if (!empty($classification = $carCatalog->getClassification())) {
            $searchParams['classification'] = $classification;
        }

        if (!empty($autoParameters = $carCatalog->getAutoParameters())) {
            $searchParams['autoParameters'] = $autoParameters;
        }

        $carCatalogSearch = $this->dm->getRepository(CarCatalog::class)
            ->findOneBy($searchParams);

        if (empty($carCatalogSearch)) {

            $parametersCriteria = $this->catalogParametersUrlHelper->buildParametersUrl([$yearId, $regionId,
                $steeringId, $seriesId, $bodyTypeId, $transmissionId, $exactModelId, $engineId, $sunroof,
                $navigation, $vsa, $doorCount, $abs, $modification, $productPeriod, $engineCapacity,
                $specModelDate, $specVinPart, $specModification, $specCatalog, $carName, $specSeries,
                $fuelType, $bodyCode, $transmission, $grade, $classification, $autoParameters]);

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/cars-parameters/?modelId=' . $modelId . $parametersCriteria,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $parameters = (object)$responseArray;

                $carCatalog->setParameters($parameters)
                    ->setDateTime();

                $carListResponse = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/cars2/?modelId=' . $modelId . $parametersCriteria,
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
