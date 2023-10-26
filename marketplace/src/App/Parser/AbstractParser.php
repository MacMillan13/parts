<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use voku\helper\HtmlDomParser;

abstract class AbstractParser
{
    protected array $elements = [];

    /**
     * @param HttpClientInterface $client
     */
    public function __construct(protected HttpClientInterface $client)
    {
    }

    /**
     * @param string $partId
     * @return array
     */
    abstract public function execute(string $partId): array;

    /**
     * @param string $url
     * @return HtmlDomParser
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function getDom(string $url): HtmlDomParser
    {
        $response = $this->client->request('GET', $url, $this->getHeaders());

        return HtmlDomParser::str_get_html($response->getContent());
    }

    protected function getUrl(string $url)
    {
       return static::SEARCH_URL . $url;
    }

    /**
     * @return array[]
     */
    private function getHeaders(): array
    {
        return [
            'headers' => [
                'Accept-Language' => 'en',
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/116.0.0.0 Safari/537.36'
            ],
        ];
    }
}
