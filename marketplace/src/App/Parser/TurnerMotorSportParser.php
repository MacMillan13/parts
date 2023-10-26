<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use BitBag\OpenMarketplace\App\Entity\ParserElement;

class TurnerMotorSportParser extends AbstractParser
{
    public const SEARCH_URL = 'https://www.turnermotorsport.com/Search?No=0&Nrpp=50&Ntt=';
    // 11128654272
    public function execute(string $partId): array
    {
        $partSearchUrl = $this->getUrl($partId);

        $html = $this->getDom($partSearchUrl);

        dd($html);

        $notFoundBlock = $html->findOne('.black-header-text')->innerhtml;

        if (str_starts_with($notFoundBlock, 'No results')) {
            return [
                'elements' => []
            ];
        }

        foreach ($html->find('.product-item ') as $row) {
            $price = $row->findOne('.price')->innerhtml;
            $text = $row->findOne('.cleanDesc')->innerhtml;
            $elementLink = $row->findOne('.product-img-link')->href;

            $brandImage = $row->findOne('.brand-image alt')->alt;
            $brand = substr($brandImage, -17);

            $parserElement = new ParserElement();
            $parserElement->setText(strip_tags($text))
                ->setPrice(strip_tags($price))
                ->setLink($elementLink)
                ->setManufacture(strip_tags($brand));

            $this->elements[] = $parserElement;
        }

        return [
            'url' => $partSearchUrl,
            'elements' => $this->elements
        ];
    }
}
