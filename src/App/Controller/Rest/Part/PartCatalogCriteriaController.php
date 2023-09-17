<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Part;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogCriteriaDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartCatalogCriteriaGroupDataQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartCatalogCriteriaController extends AbstractController
{
    #[Route(path: "part/catalog-criteria/{catalogId}/{carId}/{criteria}", name: "get_part_catalog_criteria", methods: ["GET"])]
    public function getPartCatalog(PartCatalogCriteriaDataQuery $partCatalogCriteriaDataQuery, string $catalogId, string $carId, string $criteria): Response
    {
        try {

            $partCatalog = $partCatalogCriteriaDataQuery->query($catalogId, $carId, $criteria);

            return $this->json(['data' => $partCatalog->getCatalogData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/catalog-criteria/{catalogId}/{carId}/{criteria}/{groupId}", name: "get_part_catalog_criteria_group", methods: ["GET"])]
    public function getPartCatalogGroup(PartCatalogCriteriaGroupDataQuery $partCatalogCriteriaGroupDataQuery, string $catalogId,
                                        string $carId, string $criteria, string $groupId): Response
    {
        try {

            $partCatalogGroup = $partCatalogCriteriaGroupDataQuery->query($catalogId, $carId, $criteria, $groupId);

            return $this->json(['data' => $partCatalogGroup->getCatalogData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
