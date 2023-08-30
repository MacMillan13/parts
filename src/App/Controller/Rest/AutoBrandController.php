<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest;

use BitBag\OpenMarketplace\App\Document\AutoBrand;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class AutoBrandController extends AbstractController
{
    /**
     * @param DocumentManager $dm
     */
    public function __construct(private DocumentManager $dm)
    {
    }

    #[Route(path: "auto/brand", name: "get_auto_brands", methods: ["GET"])]
    public function getAutoBrands(): Response
    {
        try {
            $autoBrands = $this->dm->getRepository(AutoBrand::class)->findAll();

            return $this->json(['data' => $autoBrands], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
