<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use BitBag\OpenMarketplace\App\Entity\ParserElement;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ECSTuningParser extends AbstractParser
{
    public const SEARCH_URL = 'https://www.ecstuning.com/Search/SiteSearch/';
    // 11128654272
    /**
     * @param string $partId
     * @return array
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute(string $partId): array
    {
        $partSearchUrl = $this->getUrl($partId);

        $html = $this->getDom($partSearchUrl);

        foreach ($html->find('.productListBox') as $row) {
            $text = $row->findOne('.cleanDesc2')->innertext;
            $price = $row->findOne('.price')->innertext;
            $brand = $row->findOne('a#brandLink')->title;
            $link = $row->findOne('.listingThumbWrap')->href;

            $parserElement = new ParserElement();
            $parserElement->setText(strip_tags($text))
                ->setPrice(strip_tags($price))
                ->setManufacture(strip_tags($brand))
                ->setLink($link);


            $this->elements[] = $parserElement;
        }

        return [
            'url' => $partSearchUrl,
            'elements' => $this->elements
        ];
    }
}
