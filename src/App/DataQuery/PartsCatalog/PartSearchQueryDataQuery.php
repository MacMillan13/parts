<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\DataQuery\AbstractDataQuery;
use BitBag\OpenMarketplace\App\Document\PartSuggestionQuery;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartSearchQueryDataQuery extends AbstractDataQuery
{
    /**
     * @param string $catalogId
     * @param string $query
     * @return PartSuggestionQuery
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $catalogId, string $query): PartSuggestionQuery
    {
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
                throw new Exception('The data does not exist');
            }
        }

        return $partSuggestionQuery;
    }
}
