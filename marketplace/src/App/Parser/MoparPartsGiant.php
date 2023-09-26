<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use BitBag\OpenMarketplace\App\Entity\ParserElement;

class MoparPartsGiant extends AbstractParser
{
    private const STORE_URL = 'https://www.moparpartsgiant.com';

    public const SEARCH_URL = self::STORE_URL . '/api/search/search-words?isConflict=false&searchText=';

    // 68440808AA
    public function execute(string $partId): array
    {
        $response = $this->client->request('GET', $this->getUrl($partId), [
            'headers' => [
                'site' => 'MPG'
            ]
        ]);

        $responseArray = $response->toArray();

        $elementLink = $responseArray['data']['redirectUrl'];

        if (strlen($elementLink) < 10) {
            return [
                'elements' => []
            ];
        }

        $html = $this->getDom('https://www.moparpartsgiant.com' . $elementLink);

        $element = $html->findOne('.part-number');

        $price = $element->findOne('.price-section-price')->innertext;
        $text = $element->findOne('.pn-detail-main-desc')->innertext
            . $element->findOne('.pn-detail-sub-desc')->innertext;
//        $elementLink = $element->findOne('.listing-text-row-moreinfo-truck a')->href;

        $parserElement = new ParserElement();
        $parserElement->setText(strip_tags($text))
            ->setPrice(strip_tags($price))
            ->setLink($elementLink);

        $this->elements[] = $parserElement;

        return [
            'url' => $elementLink,
            'elements' => $this->elements
        ];
    }
}
