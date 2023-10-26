<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoVinDataQuery as PartsCatalogCarVinDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\Sophio\CarVinDataQuery as SophioCarVinDataQuery;
use BitBag\OpenMarketplace\App\Document\AutoVin;
use BitBag\OpenMarketplace\App\Service\Sophio\CarVinDecoderService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GetAutoByVinCodeService
{
    public function __construct(private PartsCatalogCarVinDataQuery $partsCatalogVinDataQuery, private SophioCarVinDataQuery $sophioVinDataQuery,
                                private CarVinDecoderService $carVinDecoderService, private DocumentManager $documentManager)
    {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws MongoDBException
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws Exception
     */
    public function execute(string $vinCode): AutoVin
    {
        $auto = $this->partsCatalogVinDataQuery->query($vinCode);

        $isEmptyAutoData = (!empty($auto) && empty((array)$auto->getAutoData()));

        if (empty($auto) || $isEmptyAutoData) {

            $carData = $this->sophioVinDataQuery->query($vinCode);

            if (!empty($carData)) {

                //if CarVin exist but with no data
                if ($isEmptyAutoData) {
                    $carVin = $this->documentManager->getRepository(AutoVin::class)->findOneBy(['vinCode' => $vinCode]);
                } else {
                    $carVin = new AutoVin();
                }

                return $this->carVinDecoderService->decoder($carVin, $carData);
            } else {

                throw new Exception('Please search manually.');
            }
        }

        return $auto;
    }
}
