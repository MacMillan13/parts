<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\Parser\ECSTuningParser;
use BitBag\OpenMarketplace\App\Parser\PartsGeekParser;
use BitBag\OpenMarketplace\App\Parser\RockAutoParser;
use BitBag\OpenMarketplace\App\Parser\TurnerMotorSportParser;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StoreService
{
    public function __construct(protected HttpClientInterface $client)
    {
    }
    /**
     * @return array
     */
    public function getStores(): array
    {
        return [
            new RockAutoParser($this->client),
            new PartsGeekParser($this->client),
            new ECSTuningParser($this->client),
            new TurnerMotorSportParser($this->client)
        ];
    }
}
