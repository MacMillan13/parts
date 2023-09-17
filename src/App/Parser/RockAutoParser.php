<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use BitBag\OpenMarketplace\App\Entity\ParserElement;
use voku\helper\HtmlDomParser;

class RockAutoParser extends AbstractParser
{
    private const ROCK_AUTO_SEARCH = 'https://www.rockauto.com/en/partsearch/?partnum=';

    /**
     * @param string $partId
     * @return array
     */
    public function execute(string $partId): array
    {
        $partSearchUrl = self::ROCK_AUTO_SEARCH . $partId;
        $html = HtmlDomParser::file_get_html($partSearchUrl);
        $elements = [];

        foreach ($html->find('tbody') as $row) {
            $listingTexRow = $row->findOne('.listing-text-row')->innertext;
            if (!empty($listingTexRow)) {
                $price = $row->findOne('.listing-price')->innertext;
                $manufacture = $row->findOne('.listing-final-manufacturer')->innertext;
                $elementLink = $row->findOne('.listing-text-row-moreinfo-truck a')->href;

                $parserElement = new ParserElement();
                $parserElement->setText(strip_tags($listingTexRow))
                    ->setPrice(strip_tags($price))
                    ->setLink($elementLink)
                    ->setManufacture(strip_tags($manufacture));

                $elements[] = $parserElement;
            }
        }
//        83530-0e010

        return [
            'url' => $partSearchUrl,
            'elements' => $elements
        ];
    }
}
