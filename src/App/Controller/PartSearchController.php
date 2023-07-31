<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\PartSuggestionLog;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class PartSearchController extends RestAbstractController
{
    #[Route(path: "part/search/{catalogId}/{query}", name: "search_part_by_query", methods: ["GET"])]
    public function searchPartByQuery(string $catalogId, string $query)
    {
        try {
            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/groups-suggest?q=' . $query,
                $this->getHeaders()
            );

            if (!empty($responseContent = (object)$response->toArray())) {
                $partSuggestionLog = new PartSuggestionLog();
                $partSuggestionLog->setData($responseContent)
                ->setDateTime();

                $this->dm->persist($partSuggestionLog);
                $this->dm->flush();

                //TODO cron or queue saver
            } else {
                throw new \Exception('The data does not exist');
            }

            return $this->json(['data' => $responseContent], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
