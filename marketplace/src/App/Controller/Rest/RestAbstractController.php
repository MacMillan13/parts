<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller\Rest;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestAbstractController extends AbstractController
{
    public function __construct(protected DocumentManager $dm)
    {
    }
}
