<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Auto;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoCatalogDataQuery;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Service\AutoModelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class AutoCatalogController extends AbstractController
{
    #[Route(path: "auto/catalog-year/{catalogId}/{modelName}/{year}", name: "get_catalog_by_year", methods: ["GET"])]
    public function findAutoByYear(AutoCatalogDataQuery $autoCatalogDataQuery, AutoModelService $autoModelService,
                                string $catalogId, string $modelName, string $year): Response
    {
        try {
            $modelId = $autoModelService->getAutoModelId($catalogId, $modelName);
            $autoCatalog = new AutoCatalog();
            $autoCatalog->setCatalogId($catalogId);
            $autoCatalog->setModelId($modelId);

            $autoCatalogData = $autoCatalogDataQuery->query($autoCatalog);

            $parameters = $autoCatalogData->getParameters();
            //TODO узнать везде ли совпадают года по айди и тогда можно обойти один запрос
            foreach ($parameters as $param) {
                if ('year' === $param['key']) {
                    foreach ($param['values'] as $yearArray) {
                        if ($year === $yearArray['value']) {
                            $autoCatalog->setYearId($yearArray['idx']);
                            break;
                        }
                    }
                }
            }

            $autoCatalog = $autoCatalogDataQuery->query($autoCatalog);

            return $this->json(['data' => ['parameters' => $autoCatalog->getParameters(),
                'autoList' => $autoCatalog->getCarList()]], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "auto/catalog-model/{catalogId}/{modelName}", name: "get_catalog_by_model", methods: ["GET"])]
    public function getAutoCatalogByModel(AutoCatalogDataQuery $autoCatalogDataQuery, AutoModelService $autoModelService,
                                string $catalogId, string $modelName): Response
    {
        try {
            $modelId = $autoModelService->getAutoModelId($catalogId, $modelName);

            $autoCatalog = new AutoCatalog();
            $autoCatalog->setCatalogId($catalogId);
            $autoCatalog->setModelId($modelId);

            $autoCatalog = $autoCatalogDataQuery->query($autoCatalog);

            return $this->json(['data' => ['parameters' => $autoCatalog->getParameters(),
                'autoList' => $autoCatalog->getCarList()]], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "auto/catalog/{catalogId}/{modelId}", name: "get_catalog_car_parameters", methods: ["GET"])]
    public function getAutoCatalogParameters(Request $request, AutoCatalogDataQuery $autoCatalogDataQuery,
                                            string  $catalogId, string $modelId): Response
    {
        try {
            $yearId = $request->get('year');
            $regionId = $request->get('region');
            $steeringId = $request->get('steering');
            $seriesId = $request->get('series');
            $bodyTypeId = $request->get('body_type');
            $transmissionTypeId = $request->get('trans_type');
            $exactModelId = $request->get('exactModel');
            $engineId = $request->get('engine');
            $sunroof = $request->get('sunroof');
            $navigation = $request->get('navigation');
            $vsa = $request->get('vsa');
            $doorCount = $request->get('door_count');
            $abs = $request->get('abs');
            $modification = $request->get('modification');
            $productPeriod = $request->get('product_period');
            $engineCapacity = $request->get('engine_capacity');
            $carName = $request->get('car_name');
            $specSeries = $request->get('spec_series');
            $fuelType = $request->get('fuel_type');
            $bodyCode = $request->get('body_code');
            $transmission = $request->get('transmission');
            $grade = $request->get('grade');
            $classification = $request->get('classification');
            $autoParameters = $request->get('parameters');
            $specModelDate = $request->get('spec_model_date');
            $specVinPart = $request->get('spec_vin_part');
            $specModification = $request->get('spec_modification');
            $specCatalog = $request->get('spec_catalog');

            $autoCatalog = new AutoCatalog();

            $autoCatalog->setCatalogId($catalogId)
                ->setModelId($modelId)
                ->setYearId($yearId)
                ->setRegionId($regionId)
                ->setSteeringId($steeringId)
                ->setSeriesId($seriesId)
                ->setBodyTypeId($bodyTypeId)
                ->setTransmissionTypeId($transmissionTypeId)
                ->setExactModelId($exactModelId)
                ->setSunroof($sunroof)
                ->setNavigation($navigation)
                ->setVsa($vsa)
                ->setSpecModelDate($specModelDate)
                ->setSpecVinPart($specVinPart)
                ->setSpecModification($specModification)
                ->setSpecCatalog($specCatalog)
                ->setDoorCount($doorCount)
                ->setModificationId($modification)
                ->setProductPeriod($productPeriod)
                ->setEngineCapacity($engineCapacity)
                ->setAbs($abs)
                ->setCarName($carName)
                ->setSpecSeries($specSeries)
                ->setFuelType($fuelType)
                ->setBodyCode($bodyCode)
                ->setTransmission($transmission)
                ->setGrade($grade)
                ->setClassification($classification)
                ->setAutoParameters($autoParameters)
                ->setEngineId($engineId);

            $autoCatalog = $autoCatalogDataQuery->query($autoCatalog);

            return $this->json(['data' => ['parameters' => $autoCatalog->getParameters(),
                'autoList' => $autoCatalog->getCarList()]], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
