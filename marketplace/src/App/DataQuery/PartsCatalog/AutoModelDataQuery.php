<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\AutoModel;
use BitBag\OpenMarketplace\App\Helper\ElementCodeHelper;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AutoModelDataQuery extends AbstractDataQuery
{
    /**
     * @param HttpClientInterface $client
     * @param DocumentManager $dm
     * @param ElementCodeHelper $elementCodeHelper
     */
    public function __construct(HttpClientInterface $client, DocumentManager $dm,
        private ElementCodeHelper $elementCodeHelper)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @param string $catalogId
     * @return AutoModel
     * @throws MongoDBException
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function query(string $catalogId): AutoModel
    {
        $carModel = $this->dm->getRepository(AutoModel::class)->findOneBy(['catalogId' => $catalogId]);

        if (empty($carModel)) {
            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/models',
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                $carModelData = (object)$responseArray;

                foreach ($carModelData as &$model) {
                    $model['code'] = $this->elementCodeHelper->prepare($model['name']);
                }

                $carModel = new AutoModel();
                $carModel->setModels($carModelData)
                    ->setCatalogId($catalogId)
                    ->setDateTime();

                $this->dm->persist($carModel);
                $this->dm->flush();

            }
        }

        return $carModel;
    }
}
