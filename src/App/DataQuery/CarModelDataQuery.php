<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery;

use BitBag\OpenMarketplace\App\Document\CarModel;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class CarModelDataQuery extends AbstractDataQuery
{
    /**
     * @param string $catalogId
     * @return CarModel
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getCarModelList(string $catalogId): CarModel
    {
        $carModel = $this->dm->getRepository(CarModel::class)->findOneBy(['catalogId' => $catalogId]);

        if (empty($carModel)) {
            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/models',
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $carModelData = (object)$responseArray;
                $carModel = new CarModel();
                $carModel->setModels($carModelData)
                    ->setCatalogId($catalogId)
                    ->setDateTime();

                $this->dm->persist($carModel);
                $this->dm->flush();

            } else {
                throw new Exception('The Part does not exist');
            }
        }

        return $carModel;
    }
}
