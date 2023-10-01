<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Part\Vin;

use BitBag\OpenMarketplace\App\Controller\Rest\RestAbstractController;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogCriteriaDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogCriteriaGroupDataQuery;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Document\AutoVin;
use BitBag\OpenMarketplace\App\Service\GetAutoByVinCodeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class PartVinCatalogController extends RestAbstractController
{
    #[Route(path: "part/catalog-vin/{vinCode}", name: "get_part_catalog_by_vin", methods: ["GET"])]
    public function findPartCatalogByVin(PartCatalogCriteriaDataQuery $partCatalogCriteriaDataQuery,
                                      GetAutoByVinCodeService $getAutoByVinCodeService, string $vinCode): Response
    {
        try {
            $auto = $getAutoByVinCodeService->execute($vinCode);

            if ($auto->getExactMatch()) {
                $autoData = $auto->getAutoData();
                $catalogId = $autoData->catalogId;
                $carId = $autoData->carId;
                $criteria = $autoData->criteria;
                $partCatalogCriteriaData = $partCatalogCriteriaDataQuery->query($catalogId, $carId, $criteria);

                return $this->json(['data' => ['auto' => $auto, 'exactMatch' => $auto->getExactMatch(), 'catalog' => $partCatalogCriteriaData->getCatalogData()]], Response::HTTP_OK);
            } else {
                $autoCatalog = $auto->getAutoData();

                if (empty((array)$autoCatalog)) {
                    $catalogId = $auto->getCatalogId();
                    $autoCatalogRep = $this->dm->getRepository(AutoCatalog::class);
                    $autoCatalog = $autoCatalogRep->find($catalogId);
                }

                return $this->json(['data' => ['auto' => $auto, 'exactMatch' => $auto->getExactMatch(), 'catalog' => $autoCatalog->getCarList()]], Response::HTTP_OK);
            }

        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/catalog-group-vin/{vinCode}/{group}", name: "get_part_catalog_group_by_vin", methods: ["GET"])]
    public function findPartCatalogGroupByVin(GetAutoByVinCodeService $getAutoByVinCodeService, PartCatalogCriteriaGroupDataQuery $partCatalogCriteriaGroupDataQuery,
                                              PartCatalogCriteriaDataQuery $partCatalogCriteriaDataQuery,
                                              string $vinCode, string $group): Response
    {
        try {
            $auto = $getAutoByVinCodeService->execute($vinCode);

            $partCatalogCriteriaGroup = $partCatalogCriteriaGroupDataQuery->query($auto, $group);

            return $this->json(['data' => ['auto' => $auto, 'catalog' => $partCatalogCriteriaGroup->getCatalogData()]], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
