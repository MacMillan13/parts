<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery;

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
    public function getCatalogData(CarCatalog $carCatalog): CarCatalog
    {
        $carCatalogSearch = $this->dm->getRepository(CarCatalog::class)
            ->findOneBy(['catalogId' => $carCatalog->getCatalogId(), 'modelId' => $carCatalog->getModelId(), 'yearId' => $carCatalog->getYearId(),
                'regionId' => $carCatalog->getRegionId(), 'steeringId' => $carCatalog->getSteeringId(), 'seriesId' => $carCatalog->getSeriesId(),
                'bodyTypeId' => $carCatalog->getBodyTypeId(), 'transmissionTypeId' => $carCatalog->getTransmissionTypeId(),
                'exactModelId' => $carCatalog->getExactModelId(), 'engineId' => $carCatalog->getEngineId()]);

        if (empty($carCatalogSearch)) {

            $parametersCriteria = $this->catalogParametersUrlHelper->buildParametersUrl([$carCatalog->getYearId(), $carCatalog->getRegionId(),
                $carCatalog->getSteeringId(), $carCatalog->getSeriesId(), $carCatalog->getBodyTypeId(), $carCatalog->getTransmissionTypeId(),
                $carCatalog->getExactModelId(), $carCatalog->getEngineId()]);

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

                $this->dm->persist($carCatalog);
                $this->dm->flush();

            } else {

                throw new Exception('The data does not exist');
            }

            return $carCatalog;

        } else {

            return $carCatalogSearch;
        }
    }
}
