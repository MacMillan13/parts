<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Part;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartSchemaDataQuery;
use BitBag\OpenMarketplace\App\Service\AutoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartSchemaController extends AbstractController
{
    #[Route(path: "search/part/schema/{catalogId}/{modelName}/{year}/{code}/{groupName}/{schemaName}", name: "search_schema", methods: ["GET"])]
    public function search(AutoService $autoService, PartSchemaDataQuery $partSchemaDataQuery,
                           string $catalogId, string $modelName, string $year, string $code,
                           string $groupName, string $schemaName): Response
    {
        try {
            $auto = $autoService->getAutoByCode($catalogId, $modelName, $year, $code);

            $partSchema = $partSchemaDataQuery->query($catalogId, $auto->getForeignId(), $groupName, $schemaName);

            return $this->json(['data' => $partSchema->getPartData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
