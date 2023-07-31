<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\CarCatalog;
use BitBag\OpenMarketplace\App\Helper\CarCatalogUrlHelper;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class CarCatalogController extends RestAbstractController
{
    #[Route(path: "car/catalog/{catalogId}/{modelId}", name: "get_catalog_car_parameters", methods: ["GET"])]
    public function getCarCatalogParameters(Request $request, CarCatalogUrlHelper $catalogParametersUrlHelper,
                                            string  $catalogId, string $modelId): Response
    {
        try {
            $yearId = $request->get('year');
            $regionId = $request->get('region');
            $steeringId = $request->get('steering');
            $seriesId = $request->get('series');
            $bodyTypeId = $request->get('bodyType');
            $transmissionTypeId = $request->get('transmissionType');
            $exactModelId = $request->get('exactModel');
            $engineId = $request->get('engine');

            $carCatalogParameters = $this->dm->getRepository(CarCatalog::class)
                ->findOneBy(['catalogId' => $catalogId, 'modelId' => $modelId, 'yearId' => $yearId, 'regionId' => $regionId,
                    'steeringId' => $steeringId, 'seriesId' => $seriesId, 'bodyTypeId' => $bodyTypeId,
                    'transmissionTypeId' => $transmissionTypeId, 'exactModelId' => $exactModelId, 'engineId' => $engineId]);

            if (empty($carCatalogParameters)) {

                $parametersCriteria = $catalogParametersUrlHelper->buildParametersUrl([$yearId, $regionId, $steeringId,
                    $seriesId, $bodyTypeId, $transmissionTypeId, $exactModelId, $engineId]);

                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/cars-parameters/?modelId=' . $modelId . $parametersCriteria,
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $parameters = (object)$responseArray;
                    $carCatalogParameters = new CarCatalog();
                    $carCatalogParameters->setParameters($parameters)
                        ->setCatalogId($catalogId)
                        ->setModelId($modelId)
                        ->setYearId($yearId)
                        ->setRegionId($regionId)
                        ->setSteeringId($steeringId)
                        ->setSeriesId($seriesId)
                        ->setBodyTypeId($bodyTypeId)
                        ->setTransmissionTypeId($transmissionTypeId)
                        ->setExactModelId($exactModelId)
                        ->setEngineId($engineId)
                        ->setDateTime();

                    $carListResponse = $this->client->request(
                        'GET',
                        $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/cars2/?modelId=' . $modelId . $parametersCriteria,
                        $this->getHeaders()
                    );

                    if (!empty($carListArray = $carListResponse->toArray())) {
                        $carList = (object)$carListArray;
                        $carCatalogParameters->setCarList($carList);

                        //TODO cron jobs or queue for savings cars.
                    }

                    $this->dm->persist($carCatalogParameters);
                    $this->dm->flush();
                } else {
                    throw new \Exception('The data does not exist');
                }
            }

            return $this->json(['data' => $carCatalogParameters->getParameters()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
