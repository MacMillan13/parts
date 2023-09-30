<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Part\Vin;

use BitBag\OpenMarketplace\App\Controller\Rest\RestAbstractController;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogCriteriaDataQuery;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Document\AutoVin;
use BitBag\OpenMarketplace\App\Service\GetAutoByVinCodeService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class PartVinCatalogController extends RestAbstractController
{
    #[Route(path: "part/catalog-vin/{vinCode}", name: "get_menu_by_vin", methods: ["GET"])]
    public function findPartMenuByVin(PartCatalogCriteriaDataQuery $partCatalogCriteriaDataQuery,
                                      GetAutoByVinCodeService $getAutoByVinCodeService, string $vinCode): Response
    {
        try {
            $auto = $this->dm->getRepository(AutoVin::class)->findOneBy(['vinCode' => $vinCode]);

            if (empty($auto)) {
                $auto = $getAutoByVinCodeService->execute($vinCode);
            }

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
}
