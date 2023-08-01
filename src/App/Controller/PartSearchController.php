<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartSearchQueryDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartSearchSidDataQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class PartSearchController extends AbstractController
{
    #[Route(path: "part/search/{catalogId}/{query}", name: "search_part_by_query", methods: ["GET"])]
    public function searchPartByQuery(PartSearchQueryDataQuery $partSearchQueryDataQuery, string $catalogId,
                                      string $query): Response
    {
        try {

            $partSuggestionQuery = $partSearchQueryDataQuery->query($catalogId, $query);

            return $this->json(['data' => $partSuggestionQuery->getData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/search-sid/{catalogId}/{carId}/{sid}", name: "search_part_by_sid", methods: ["GET"])]
    public function searchPartBySid(PartSearchSidDataQuery $partSearchSidDataQuery, string $sid, string $carId, string $catalogId): Response
    {
        try {

            $partSuggestion = $partSearchSidDataQuery->query($sid, $carId, $catalogId);

            return $this->json(['data' => $partSuggestion->getData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
