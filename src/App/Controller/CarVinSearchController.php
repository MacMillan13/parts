<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\CarVinDataQuery as PartsCatalogCarVinDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\Sophio\CarVinDataQuery as SophioCarVinDataQuery;
use BitBag\OpenMarketplace\App\Document\CarVin;
use BitBag\OpenMarketplace\App\Service\Sophio\CarVinDecoderService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class CarVinSearchController extends AbstractController
{
    /**
     * @param PartsCatalogCarVinDataQuery $partsCatalogVinDataQuery
     * @param SophioCarVinDataQuery $sophioVinDataQuery
     * @param CarVinDecoderService $carVinDecoderService
     * @param string $vinCode
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    #[Route(path: "search/vin/{vinCode}", name: "search_by_vin", methods: ["GET"])]
    public function searchByVinCode(PartsCatalogCarVinDataQuery $partsCatalogVinDataQuery, SophioCarVinDataQuery $sophioVinDataQuery,
                                    CarVinDecoderService $carVinDecoderService, DocumentManager $documentManager, string $vinCode): Response
    {
        try {

            $auto = $partsCatalogVinDataQuery->query($vinCode);

            $isEmptyAutoData = (!empty($auto) && empty((array)$auto->getAutoData()));

            if (empty($auto) || $isEmptyAutoData) {

                $carData = $sophioVinDataQuery->query($vinCode);

                if (!empty($carData)) {

                    //if CarVin exist but with no data
                    if ($isEmptyAutoData) {
                        $carVin = $documentManager->getRepository(CarVin::class)->findOneBy(['vinCode' => $vinCode]);
                    } else {
                        $carVin = new CarVin();
                    }

                    $autoData = $carVinDecoderService->decoder($carVin, $carData);
                    return $this->json(['data' => $autoData, 'exactMatch' => false], Response::HTTP_OK);

                } else {

                    throw new \Exception('Please search manually.');
                }
            }

            return $this->json(['exactMatch' => $auto->getExactMatch(), 'data' => $auto->getAutoData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
