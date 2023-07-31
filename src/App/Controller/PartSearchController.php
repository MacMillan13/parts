<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\PartSuggestion;
use BitBag\OpenMarketplace\App\Document\PartSuggestionQuery;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class PartSearchController extends RestAbstractController
{
    #[Route(path: "part/search/{catalogId}/{query}", name: "search_part_by_query", methods: ["GET"])]
    public function searchPartByQuery(string $catalogId, string $query): Response
    {
        try {
            $partSuggestionQuery = $this->dm->getRepository(PartSuggestionQuery::class)->findOneBy(['catalogId' => $catalogId,
                'query' => $query]);

            if (empty($partSuggestionQuery)) {
                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups-suggest?q=' . $query,
                    $this->getHeaders()
                );

                if (!empty($responseContent = (object)$response->toArray())) {
                    $partSuggestionQuery = new PartSuggestionQuery();
                    $partSuggestionQuery->setData($responseContent)
                        ->setQuery($query)
                        ->setCatalogId($catalogId)
                        ->setDateTime();

                    $this->dm->persist($partSuggestionQuery);
                    $this->dm->flush();

                    //TODO cron or queue saver
                } else {
                    throw new \Exception('The data does not exist');
                }
            }

            return $this->json(['data' => $partSuggestionQuery->getData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route(path: "part/search-sid/{catalogId}/{carId}/{sid}", name: "search_part_by_sid", methods: ["GET"])]
    public function searchPartBySid(string $sid, string $carId, string $catalogId): Response
    {
        try {
            $partSuggestion = $this->dm->getRepository(PartSuggestion::class)->findOneBy(['catalogId' => $catalogId,
                'sid' => $sid, 'carId' => $carId]);

            if (empty($partSuggestion)) {
                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups-by-sid?sid=' . $sid
                    . '&carId=' . $carId,
                    $this->getHeaders()
                );

                if (!empty($responseContent = (object)$response->toArray())) {
                    $partSuggestion = new PartSuggestion();
                    $partSuggestion->setData($responseContent)
                        ->setSid($sid)
                        ->setCatalogId($catalogId)
                        ->setCarId($carId)
                        ->setDateTime();

                    $this->dm->persist($partSuggestion);
                    $this->dm->flush();

                    //TODO cron or queue saver
                } else {
                    throw new \Exception('The data does not exist');
                }
            }

            return $this->json(['data' => $partSuggestion->getData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
