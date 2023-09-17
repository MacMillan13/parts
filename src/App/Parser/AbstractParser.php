<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

abstract class AbstractParser
{
    /**
     * @param string $partId
     * @return array
     */
    abstract public function execute(string $partId): array;
}
