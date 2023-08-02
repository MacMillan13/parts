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

        $carCatalogSearch = $this->dm->getRepository(CarCatalog::class)
            ->findOneBy($searchParams);

        if (empty($carCatalogSearch)) {

            $parametersCriteria = $this->catalogParametersUrlHelper->buildParametersUrl([$yearId, $regionId,
                $steeringId, $seriesId, $bodyTypeId, $transmissionId, $exactModelId, $engineId]);

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
