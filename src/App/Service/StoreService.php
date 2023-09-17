<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use BitBag\OpenMarketplace\App\Parser\RockAutoParser;

class StoreService
{
    public function getStores(): array
    {
        return [
            new RockAutoParser()
        ];
    }
}
