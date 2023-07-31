<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use BitBag\OpenMarketplace\App\Document\CarModel;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[Route(path: "/api/v3/")]
class CarModelController extends RestAbstractController
{
    #[Route(path: "car/model/{catalogId}", name: "get_car_models", methods: ["GET"])]
    public function search(string $catalogId): Response
    {
        try {
            $carModel = $this->dm->getRepository(CarModel::class)->findOneBy(['catalogId' => $catalogId]);

            if (empty($carModel)) {
                $response = $this->client->request(
                    'GET',
                    $_ENV['PART_CATALOG_API'] . 'catalogs/' . $catalogId . '/models',
                    $this->getHeaders()
                );

                if (!empty($responseArray = $response->toArray())) {
                    $carModelData = (object)$responseArray;
                    $carModel = new CarModel();
                    $carModel->setModels($carModelData)
                        ->setCatalogId($catalogId)
                        ->setDateTime();

                    $this->dm->persist($carModel);
                    $this->dm->flush();

                } else {
                    throw new \Exception('The Part does not exist');
                }
            }

            return $this->json(['data' => $carModel->getModels()], Response::HTTP_OK);

        } catch (ClientException $exception) {

            return $this->json(['error' => 'Sorry. We cannot get needed data.'], Response::HTTP_BAD_REQUEST);

        } catch (\Exception|TransportExceptionInterface $exception) {

            return $this->json(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
