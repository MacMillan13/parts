<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

use Doctrine\ODM\MongoDB\DocumentManager;

class AutoSaverService
{
    public function __construct(DocumentManager $dm)
    {}

}
