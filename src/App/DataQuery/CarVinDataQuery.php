<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery;

use BitBag\OpenMarketplace\App\Document\CarVin;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CarVinDataQuery extends AbstractDataQuery
{
    /**
     * @param string $vinCode
     * @return CarVin
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function query(string $vinCode): CarVin
    {
        $auto = $this->dm->getRepository(CarVin::class)->findOneBy(['vinCode' => $vinCode]);

        if (empty($auto)) {

            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'car/info?q=' . $vinCode,
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $autoData = (object)$responseArray[0];
                $auto = new CarVin();
                $auto->setAutoData($autoData)
                    ->setDateTime()
                    ->setVinCode($vinCode);

                $this->dm->persist($auto);
                $this->dm->flush();
            } else {

                throw new Exception('The Vin Code does not exist');
            }
        }

        return $auto;
    }
}
