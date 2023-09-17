<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

abstract class AbstractParser
{
    abstract public function execute(): array;
}
