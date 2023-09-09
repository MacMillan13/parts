<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoCatalogDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogGroupDataQuery;
use BitBag\OpenMarketplace\App\Document\Auto;
use BitBag\OpenMarketplace\App\Document\AutoCatalog;
use BitBag\OpenMarketplace\App\Document\PartCatalog;
use BitBag\OpenMarketplace\App\Document\PartCatalogGroup;
use BitBag\OpenMarketplace\App\Service\AutoModelService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartCatalogController extends RestAbstractController
{
    #[Route(path: "part/catalog-modification/{catalogId}/{modelName}/{year}/{modification}", name: "get_catalog_by_modification", methods: ["GET"])]
    public function findCatalogByModification(DocumentManager $documentManager, AutoCatalogDataQuery $autoCatalogDataQuery, AutoModelService $autoModelService,
                                              PartCatalogDataQuery $partCatalogDataQuery, string $catalogId, string $modelName,
                                              string $year, string $modification): Response
    {
        try {
            $autoRep = $documentManager->getRepository(Auto::class);

            $auto = $autoRep->findOneBy(['catalogId' => $catalogId, 'modelName' => $modelName, 'year' => $year, 'code' => $modification]);

            if (empty($auto)) {

                $modelId = $autoModelService->getAutoModelId($catalogId, $modelName);

                $autoCatalog = new AutoCatalog();
                $autoCatalog->setCatalogId($catalogId);
                $autoCatalog->setModelId($modelId);

                $autoCatalog = $autoCatalogDataQuery->query($autoCatalog);
                $autoList = $autoCatalog->getCarList();

                foreach ($autoList as $oneAuto) {
                    if (!empty($oneAuto['code']) && $oneAuto['code'] === $modification) {
                        $auto = $oneAuto;
                    }
                }
            }

            $partCatalog = $this->dm->getRepository(PartCatalog::class)->findOneBy(['catalogId' => $catalogId, 'carId' => $auto->getForeignId()]);

            if (empty($partCatalog)) {
                $partCatalog = $partCatalogDataQuery->query($catalogId, $auto->getForeignId());
            }

            return $this->json(['data' => ['auto' => $auto, 'catalog' => $partCatalog->getCatalogData()]], Response::HTTP_OK);

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

    #[Route(path: "part/catalog/{catalogId}/{carId}/{groupId}", name: "get_common_part_catalog_group", methods: ["GET"])]
    public function getPartCatalogGroup(PartCatalogGroupDataQuery $partCatalogGroupDataQuery, string $catalogId, string $carId, string $groupId): Response
    {
        try {
            $partCatalogGroup = $this->dm->getRepository(PartCatalogGroup::class)->findOneBy(['catalogId' => $catalogId,
                'carId' => $carId, 'groupId' => $groupId]);

            if (empty($partCatalogGroup)) {
                $partCatalogGroup = $partCatalogGroupDataQuery->query($catalogId, $carId, $groupId);
            }

            return $this->json(['data' => $partCatalogGroup->getCatalogData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
