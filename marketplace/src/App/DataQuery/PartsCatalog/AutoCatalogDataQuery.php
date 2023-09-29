<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\Auto;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Document\AutoModel;
use BitBag\OpenMarketplace\App\Helper\AutoCatalogUrlHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AutoCatalogDataQuery extends AbstractDataQuery
{
    /**
     * @param HttpClientInterface $client
     * @param DocumentManager $dm
     * @param AutoCatalogUrlHelper $catalogParametersUrlHelper
     */
    public function __construct(HttpClientInterface          $client, DocumentManager $dm,
                                private AutoCatalogUrlHelper $catalogParametersUrlHelper)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param AutoCatalog $autoCatalog
     * @return AutoCatalog
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(AutoCatalog $autoCatalog): AutoCatalog
    {
        $searchParams = [];

        $searchParams['catalogId'] = $autoCatalog->getCatalogId();

        $searchParams['modelId'] = $autoCatalog->getModelId();

        $searchParams['yearId'] = $autoCatalog->getYearId();

        $searchParams['regionId'] = $autoCatalog->getRegionId();

        $searchParams['steeringId'] = $autoCatalog->getSteeringId();

        $searchParams['seriesId'] = $autoCatalog->getSeriesId();

        $searchParams['bodyTypeId'] = $autoCatalog->getBodyTypeId();

        $searchParams['transmissionTypeId'] = $autoCatalog->getTransmissionTypeId();

        $searchParams['modelName'] = $autoCatalog->getModelName();

        $searchParams['engineId'] = $autoCatalog->getEngineId();

        $searchParams['sunroof'] = $autoCatalog->getSunroof();

        $searchParams['navigation'] = $autoCatalog->getNavigation();

        $searchParams['vsa'] = $autoCatalog->getVsa();

        $searchParams['doorCount'] = $autoCatalog->getDoorCount();

        $searchParams['abs'] = $autoCatalog->getAbs();

        $searchParams['modification'] = $autoCatalog->getModificationId();

        $searchParams['productPeriod'] = $autoCatalog->getProductPeriod();

        $searchParams['engineCapacity'] = $autoCatalog->getEngineCapacity();

        $searchParams['specModelDate'] = $autoCatalog->getSpecModelDate();

        $searchParams['specVinPart'] = $autoCatalog->getSpecVinPart();

        $searchParams['specModification'] = $autoCatalog->getSpecModification();

        $searchParams['specCatalog'] = $autoCatalog->getSpecCatalog();

        $searchParams['carName'] = $autoCatalog->getCarName();

        $searchParams['specSeries'] = $autoCatalog->getSpecSeries();

        $searchParams['fuelType'] = $autoCatalog->getFuelType();

        $searchParams['bodyCode'] = $autoCatalog->getBodyCode();

        $searchParams['transmission'] = $autoCatalog->getTransmission();

        $searchParams['grade'] = $autoCatalog->getGrade();

        $searchParams['classification'] = $autoCatalog->getClassification();

        $searchParams['autoParameters'] = $autoCatalog->getAutoParameters();

        $autoCatalogSearch = $this->dm->getRepository(AutoCatalog::class)
            ->findOneBy($searchParams);

        if (empty($autoCatalogSearch)) {

            $autoModel = $this->dm->getRepository(AutoModel::class);
            $autoModel = $autoModel->findOneBy(['catalogId' => $autoCatalog->getCatalogId()]);

            foreach ($autoModel->getModels() as $model) {
                if (strtolower($model['name']) === $autoCatalog->getModelName()) {
                    $autoCatalog->setModelId($model['id']);
                    break;
                }
            }

            $parametersCriteria = $this->catalogParametersUrlHelper->buildParametersUrl([$autoCatalog->getYearId(),
                $autoCatalog->getRegionId(), $autoCatalog->getSteeringId(), $autoCatalog->getSeriesId(), $autoCatalog->getBodyTypeId(),
                $autoCatalog->getTransmissionTypeId(), $autoCatalog->getEngineId(), $autoCatalog->getSunroof(),
                $autoCatalog->getNavigation(), $autoCatalog->getVsa(), $autoCatalog->getDoorCount(), $autoCatalog->getAbs(),
                $autoCatalog->getModificationId(), $autoCatalog->getProductPeriod(), $autoCatalog->getEngineCapacity(), $autoCatalog->getSpecModelDate(),
                $autoCatalog->getSpecVinPart(), $autoCatalog->getSpecModification(), $autoCatalog->getSpecCatalog(), $autoCatalog->getCarName(),
                $autoCatalog->getSpecSeries(), $autoCatalog->getFuelType(), $autoCatalog->getBodyCode(), $autoCatalog->getTransmission(),
                $autoCatalog->getGrade(), $autoCatalog->getClassification(), $autoCatalog->getAutoParameters()]);

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $autoCatalog->getCatalogId() . '/cars-parameters/?modelId=' . $autoCatalog->getModelId() . $parametersCriteria,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $parameters = (object)$responseArray;

                $autoCatalog->setParameters($parameters)
                    ->setDateTime();

                $autoListResponse = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $autoCatalog->getCatalogId() . '/cars2/?modelId=' . $autoCatalog->getModelId() . $parametersCriteria,
                    $this->getHeaders()
                );

                if (!empty($autoListArray = $autoListResponse->toArray())) {
                    foreach ($autoListArray[0]['parameters'] as $parameter) {
                        if ($parameter['key'] === 'year' && !empty($autoCatalog->getYearId())) {
                            $autoCatalog->setYear($parameter['value']);
                        }
                        if ($parameter['key'] === 'modelId' && !empty($autoCatalog->getModelId())) {
                            $autoCatalog->setModel(strtolower($parameter['value']));
                        }
                    }

                    $autoRep = $this->dm->getRepository(Auto::class);

                    foreach ($autoListArray as &$auto) {

                        $trimmedName = trim($auto['name']);
                        $code = str_replace(' ', '_', strtolower($trimmedName));
                        $auto['code'] = $code;

                        if (!empty($autoRep->findOneBy(['foreignId' => $auto['id']]))) {
                            continue;
                        }

                        $newAuto = new Auto();
                        $newAuto->setCatalogId($auto['catalogId'])
                            ->setName($trimmedName)
                            ->setBrand($auto['brand'])
                            ->setCriteria($auto['criteria'])
                            ->setFrame($auto['frame'])
                            ->setDescription($auto['description'])
                            ->setForeignId($auto['id'])
                            ->setModelId($auto['modelId'])
                            ->setModelName(strtolower($auto['modelName']))
                            ->setVin($auto['vin'])
                            ->setParameters($auto['parameters'])
                            ->setCode($code)
                            ->setYear($autoCatalog->getYear())
                            ->setDateTime();

                        $this->dm->persist($newAuto);
                    }

                    $autoList = (object)$autoListArray;
                    $autoCatalog->setCarList($autoList);

                    //TODO cron jobs or queue for savings cars.
                }

                $notIdentifiedAutoCatalog = clone $autoCatalog;

                $this->dm->persist($notIdentifiedAutoCatalog);

                $this->dm->flush();
            }

            return $autoCatalog;

        } else {

            return $autoCatalogSearch;
        }
    }
}
