<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\CarBrand;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class CarBrandController extends RestAbstractController
{
    #[Route(path: "car/brand", name: "get_catalog_car", methods: ["GET"])]
    public function getCarCatalog(): Response
    {
        try {
            $carCatalog = $this->dm->getRepository(CarBrand::class)->findAll();

            return $this->json(['data' => $carCatalog], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
