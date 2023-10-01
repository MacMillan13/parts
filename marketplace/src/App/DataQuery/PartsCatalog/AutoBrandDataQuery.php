<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use BitBag\OpenMarketplace\App\Document\AutoBrand;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AutoBrandDataQuery extends AbstractDataQuery
{
    const AUTO_PRIORITY = [
        'jeep' => 9,
        'bmw' => 9,
        'audi' => 9,
        'dodge' => 9,
        'lexus' => 9,
        'infiniti' => 9,
        'toyota' => 9,
        'honda' => 9,
        'mercedes' => 9,
        'hyundai' => 9,
        'vw' => 9,
        'cadillac' => 9,
        'chevrolet' => 9,
        'gmc' => 9,
        'citroen' => 9,
        'nissan' => 9,
        'ford' => 9,
        'mazda' => 9,
        'volvo' => 9,
        'kia' => 9,
        'mitsubishi' => 9,
        'subaru' => 8,
        'opel' => 8,
        'suzuki' => 8,
        'chrysler' => 8,
        'seat' => 8,
        'renault' => 8,
        'porsche' => 7,
        'jaguar' => 7,
        'rolls-royce' => 7,
        'bentley' => 7,
        'hummer' => 7,
        'land-rover' => 7,
        'mini' => 6,
        'alfa-romeo' => 6,
        'smart' => 6,
        'fiat' => 5,
        'peugeot' => 5,
        'skoda' => 5,
        'bmw-moto' => 4,
        'pontiac' => 3,
        'ssangyong' => 3,
        'hino-light' => 2,
        'hino-heavy' => 2,
        'volvo-trucks' => 2,
        'iveco' => 2,
        'renault-trucks' => 2,
        'isuzu' => 2,
        'man' => 2,
        'daf' => 2,
        'scania' => 2,
        'vauxhall' => 1,
        'saab' => 1,
        'plymouth' => 0,
        'dacia' => 0,
        'hyundai-korea' => 0,
        'chery' => 0,
        'daewoo' => 0,
        'kia-korea' => 0,
    ];

    public function __construct(HttpClientInterface $client, DocumentManager $dm)
    {
        parent::__construct($client, $dm);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws MongoDBException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function query(): array
    {
        $autoBrand = $this->dm->getRepository(AutoBrand::class)->findAllBrandsOrderByPriority();

        if (empty($autoBrand)) {
            $response = $this->client->request(
                'GET',
                $_ENV['PART_CATALOG_API'] . 'catalogs',
                $this->getHeaders()
            );

            if (!empty($responseArray = $response->toArray())) {
                foreach ($responseArray as $brand) {
                    $newBrand = new AutoBrand();
                    $newBrand->setModelsCount($brand['modelsCount'])
                        ->setCatalogId($brand['id'])
                        ->setName($brand['name'])
                        ->setPriority(self::AUTO_PRIORITY[$brand['id']])
                        ->setDateTime();

                    $this->dm->persist($newBrand);
                }
            }

            $this->dm->flush();

            $autoBrand = $this->dm->getRepository(AutoBrand::class)->findAllBrandsOrderByPriority();
        }

        return $autoBrand;
    }
}
