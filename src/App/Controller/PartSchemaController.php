<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartSchemaDataQuery;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartSchemaController extends RestAbstractController
{
    #[Route(path: "search/part/schema/{catalogId}/{carId}/{groupId}", name: "search_part", methods: ["GET"])]
    public function search(PartSchemaDataQuery $partSchemaDataQuery, string $catalogId, string $carId, string $groupId): Response
    {
        try {

            $partSchema = $partSchemaDataQuery->query($catalogId, $carId, $groupId);

            return $this->json(['data' => $partSchema->getPartData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
