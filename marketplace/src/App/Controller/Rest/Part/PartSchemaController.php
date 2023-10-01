<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Part;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\PartSchemaDataQuery;
use BitBag\OpenMarketplace\App\Service\AutoService;
use BitBag\OpenMarketplace\App\Service\GetAutoByVinCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class PartSchemaController extends AbstractController
{
    #[Route(path: "part/schema/{catalogId}/{modelName}/{year}/{code}/{groupName}/{schemaName}", name: "get_schema", methods: ["GET"])]
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

    #[Route(path: "part/schema-vin/{vinCode}/{group}/{schemaName}", name: "get_schema_by_vin", methods: ["GET"])]
    public function searchByVin(GetAutoByVinCodeService $getAutoByVinCodeService, PartSchemaDataQuery $partSchemaDataQuery,
                                string $vinCode, string $group, string $schemaName): Response
    {
        try {
            $auto = $getAutoByVinCodeService->execute($vinCode);

            if ($auto->getExactMatch()) {
                $autoData = $auto->getAutoData();

                $partSchema = $partSchemaDataQuery->query($autoData->catalogId, $autoData->carId, $group, $schemaName);

                return $this->json(['data' => $partSchema->getPartData()], Response::HTTP_OK);
            }

            throw new \Exception('Current auto does not have an exact match');

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
