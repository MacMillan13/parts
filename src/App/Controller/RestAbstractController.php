<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use \Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestAbstractController extends AbstractController
{
    /**
     * @return \string[][]
     */
    protected function getHeaders(): array
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Language' => 'en',
                'Authorization' => 'OEM-API-08F0004D-C01F-4912-958E-CEF55A4306C9',
            ],
        ];
    }
}
