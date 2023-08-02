<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\CarVin;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CarVinDataQuery extends AbstractDataQuery
{
    /**
     * @param string $vinCode
     * @return CarVin|null
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws MongoDBException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $vinCode): ?CarVin
    {
        $auto = $this->dm->getRepository(CarVin::class)->getVinDataWithCatalog($vinCode);

        if (empty($auto)) {

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'car/info?q=' . $vinCode,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $autoData = (object)$responseArray[0];
                $carVin = new CarVin();
                $carVin->setAutoData($autoData)
                    ->setDateTime()
                    ->setExactMatch(true)
                    ->setVinCode($vinCode);

                $this->dm->persist($carVin);
                $this->dm->flush();

                return $carVin;
            } else {
                return null;
            }
        }

        if (!empty($auto['catalog'])) {
            $autoData = $auto['catalog'];
        } else if (!empty($auto['autoData'])) {
            $autoData = $auto['autoData'];
        } else {
            $autoData = [];
        }

        $carVin = new CarVin();
        $carVin->setExactMatch($auto['exactMatch'])
            ->setVinCode($auto['vinCode'])
            ->setAutoData((object)$autoData);

        return $carVin;
    }
}
