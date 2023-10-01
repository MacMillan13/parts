<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest\Auto;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\AutoBrandDataQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class AutoBrandController extends AbstractController
{
    /**
     * @param AutoBrandDataQuery $autoBrandDataQuery
     */
    public function __construct(private AutoBrandDataQuery $autoBrandDataQuery)
    {
    }

    #[Route(path: "auto/brand", name: "get_auto_brands", methods: ["GET"])]
    public function getAutoBrands(): Response
    {
        try {
            $autoBrands = $this->autoBrandDataQuery->query();

            return $this->json(['data' => $autoBrands], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
