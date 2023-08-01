<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\CarVinDataQuery as PartsCatalogCarVinDataQuery;
use BitBag\OpenMarketplace\App\DataQuery\Sophio\CarVinDataQuery as SophioCarVinDataQuery;
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
     * @param PartsCatalogCarVinDataQuery $carVinDataQuery
     * @param SophioCarVinDataQuery $carVinMissDataQuery
     * @param string $vinCode
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    #[Route(path: "search/vin/{vinCode}", name: "search_by_vin", methods: ["GET"])]
    public function searchByVinCode(PartsCatalogCarVinDataQuery $partsCatalogVinDataQuery, SophioCarVinDataQuery $sophioVinDataQuery, string $vinCode): Response
    {
        try {

            $auto = $partsCatalogVinDataQuery->query($vinCode);

            if (empty($auto)) {
                $vinData = $sophioVinDataQuery->query($vinCode);

                if (!empty($vinData)) {

                } else {
                    throw new \Exception('Please search manually.');
                }
            }

            return $this->json(['data' => $auto->getAutoData()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
