<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Part;

use BitBag\OpenMarketplace\App\Controller\Rest\RestAbstractController;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogGroupDataQuery;
use BitBag\OpenMarketplace\App\Document\PartCatalog;
use BitBag\OpenMarketplace\App\Document\PartCatalogGroup;
use BitBag\OpenMarketplace\App\Helper\ElementCodeHelper;
use BitBag\OpenMarketplace\App\Service\AutoService;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class PartCatalogController extends RestAbstractController
{
    #[Route(path: "part/catalog-code/{catalogId}/{modelName}/{year}/{code}", name: "get_part_catalog_by_code", methods: ["GET"])]
    public function findCatalogByCode(AutoService $autoService, PartCatalogDataQuery $partCatalogDataQuery, string $catalogId,
                                              string $modelName, string $year, string $code): Response
    {
        try {
            $auto = $autoService->getAutoByCode($catalogId, $modelName, $year, $code);

            $partCatalog = $partCatalogDataQuery->query($catalogId, $auto->getForeignId());

            return $this->json(['data' => ['auto' => $auto, 'catalog' => $partCatalog->getCatalogData()]], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/catalog-group/{catalogId}/{modelName}/{year}/{code}/{group}", name: "get_part_catalog_by_group", methods: ["GET"])]
    public function findPartCatalogGroup(PartCatalogGroupDataQuery $partCatalogGroupDataQuery, AutoService $autoService, PartCatalogDataQuery $partCatalogDataQuery,
                                         ElementCodeHelper         $elementCodeHelper, string $catalogId, string $modelName, string $year, string $code, string $group): Response
    {
        try {
            $auto = $autoService->getAutoByCode($catalogId, $modelName, $year, $code);

            $partCatalogGroup = $this->dm->getRepository(PartCatalogGroup::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $auto->getForeignId(), 'groupName' => $group]);

            if (empty($partCatalogGroup)) {

                $partCatalog = $partCatalogDataQuery->query($catalogId, $auto->getForeignId());

                foreach ($partCatalog->getCatalogData() as $partCatalog) {
                    if ($partCatalog['code'] === $group) {
                        $partCatalogGroup = $partCatalogGroupDataQuery->query($catalogId, $auto->getForeignId(), $partCatalog['id'], $group);
                        break;
                    }
                }
            }

            if (empty($partCatalogGroup)) {
                throw new \Exception('Error, no data');
            }

            return $this->json(['data' => ['auto' => $auto, 'catalog' => $partCatalogGroup->getCatalogData()]], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/catalog-group/{catalogId}/{modelName}/{year}/{code}/{group}/{subgroup}", name: "get_part_catalog_by_subgroup", methods: ["GET"])]
    public function findPartSubgroupCatalog(PartCatalogGroupDataQuery $partCatalogGroupDataQuery, AutoService $autoService, PartCatalogDataQuery $partCatalogDataQuery,
                                         string $catalogId, string $modelName, string $year, string $code, string $group, string $subgroup): Response
    {
        try {
            $auto = $autoService->getAutoByCode($catalogId, $modelName, $year, $code);

            $partCatalogSubgroup = $this->dm->getRepository(PartCatalogGroup::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $auto->getForeignId(), 'group' => $subgroup]);

            if (empty($partCatalogSubgroup)) {

                $partCatalogGroup = $this->dm->getRepository(PartCatalogGroup::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $auto->getForeignId(), 'group' => $group]);

                if (empty($partCatalogGroup)) {

                    $partCatalog = $partCatalogDataQuery->query($catalogId, $auto->getForeignId());

                    foreach ($partCatalog->getCatalogData() as $partGroup) {
                        if (strtolower($partGroup['name']) === $group) {
                            $partCatalogGroup = $partCatalogGroupDataQuery->query($catalogId, $auto->getForeignId(), $partGroup['id'], $group);
                            break;
                        }
                    }
                }

                foreach ($partCatalogGroup->getCatalogData() as $partGroup) {
                    if (strtolower($partGroup['name']) === $group) {
                        $partCatalogSubgroup = $partCatalogGroupDataQuery->query($catalogId, $auto->getForeignId(), $partGroup['id'], $subgroup);
                        break;
                    }
                }

            }
            return $this->json(['data' => ['auto' => $auto, 'catalog' => $partCatalogSubgroup->getCatalogData()]], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/catalog/{catalogId}/{carId}", name: "get_common_part_catalog", methods: ["GET"])]
    public function getPartCatalog(PartCatalogDataQuery $partCatalogDataQuery, string $catalogId, string $carId): Response
    {
        try {
            $partCatalog = $this->dm->getRepository(PartCatalog::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $carId]);

            if (empty($partCatalog)) {
                $partCatalog = $partCatalogDataQuery->query($catalogId, $carId);
            }

            return $this->json(['data' => $partCatalog->getCatalogData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
