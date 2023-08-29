<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest;

use BitBag\OpenMarketplace\App\Document\Auto;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/api/v3/")]
class AutoController extends AbstractController
{
    public function __construct(private DocumentManager $documentManager)
    {
    }

    #[Route(path: "{catalogId}/{modelName}/{modification}", name: "get_catalog_car_parameters", methods: ["GET"])]
    public function findAuto(string $catalogId, string $modelName, string $modification): Response
    {
        try {

            $autoRep = $this->documentManager->getRepository(Auto::class);
            $auto = $autoRep->findOneBy(['catalogId' => $catalogId, 'modelName' => $modelName, 'code' => $modification]);

            if (empty($auto)) {
                throw new \Exception("Sorry. We can't find the car.");
            }

            return $this->json(['data' => $auto], Response::HTTP_OK);

        } catch (\Exception $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
