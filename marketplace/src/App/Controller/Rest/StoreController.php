<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest;

use BitBag\OpenMarketplace\App\Service\StoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route(path: "/api/v3/")]
class StoreController extends AbstractController
{
    public function __construct(protected HttpClientInterface $client, protected StoreService $storeService)
    {
    }

    #[Route(path: "store/{storeId}/{partId}", name: "search_part", methods: ["GET"])]
    public function getDataFromStore(string $storeId, string $partId): Response
    {
        try {
            $stores = $this->storeService->getStores();
            $data = $stores[$storeId]->execute($partId);

            return $this->json(['data' => $data], Response::HTTP_OK);

        } catch (\Exception $exception) {
            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
