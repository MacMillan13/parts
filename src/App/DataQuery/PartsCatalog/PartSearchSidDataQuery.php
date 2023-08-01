<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\PartSuggestion;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PartSearchSidDataQuery extends AbstractDataQuery
{
    /**
     * @param string $sid
     * @param string $carId
     * @param string $catalogId
     * @return PartSuggestion
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $sid, string $carId, string $catalogId): PartSuggestion
    {
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

        return $partSuggestion;
    }
}
