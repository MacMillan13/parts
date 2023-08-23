<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest;

use BitBag\OpenMarketplace\App\DataQuery\PartsCatalog\CarModelDataQuery;
use BitBag\OpenMarketplace\App\Document\CarModel;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class AutoModelController extends RestAbstractController
{
    #[Route(path: "auto/model/{catalogId}", name: "get_car_models", methods: ["GET"])]
    public function search(CarModelDataQuery $carModelDataQuery, string $catalogId): Response
    {
        try {
            $carModel = $this->dm->getRepository(CarModel::class)->findOneBy(['catalogId' => $catalogId]);

            if (empty($carModel)) {
                $carModel = $carModelDataQuery->query($catalogId);
            }

            return $this->json(['data' => $carModel->getModels()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
