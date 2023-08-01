<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DataQuery\PartsCatalog;

use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractDataQuery
{
    /**
     * @param HttpClientInterface $client
     * @param DocumentManager $dm
     */
    public function __construct(protected HttpClientInterface $client, protected DocumentManager $dm)
    {
    }

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
