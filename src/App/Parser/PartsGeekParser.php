<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use BitBag\OpenMarketplace\App\Entity\ParserElement;

class PartsGeekParser extends AbstractParser
{
    public const URL = 'https://www.partsgeek.com/ss/?i=1&ssq=';
    // Test code 11378662525
    /**
     * @param string $partId
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function execute(string $partId): array
    {
        $partSearchUrl = $this->getUrl($partId);

        $html = $this->getDom($partSearchUrl);

        foreach ($html->find('.product') as $row) {
            $text = $row->findOne('.product-title')->innertext;
            $price = $row->findOne('.product-price')->innertext;
            $productAttributesHeading = $row->findMulti('.product-attribute-heading');
            $brand = strip_tags($productAttributesHeading[1]->parent()->innertext);
            $brand = substr($brand, 7);

            $parserElement = new ParserElement();
            $parserElement->setText(strip_tags($text))
                ->setPrice(strip_tags($price))
                ->setManufacture(strip_tags($brand));

            $this->elements[] = $parserElement;
        }

        return [
            'url' => $partSearchUrl,
            'elements' => $this->elements
        ];
    }
}
