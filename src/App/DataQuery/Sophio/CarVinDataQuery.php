<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\Sophio;


class CarVinDataQuery extends AbstractDataQuery
{
    public function query(string $vinCode): array
    {
        $carResponse = $this->client->request(
            'GET',
            $_ENV['SOPHIO_API'] . 'catalog-2/vin/' . $vinCode,
            $this->getHeaders()
        );

        return $carResponse->toArray();
    }
}
