<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use BitBag\OpenMarketplace\App\Entity\ParserElement;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RockAutoParser extends AbstractParser
{
    public const SEARCH_URL = 'https://www.rockauto.com/en/partsearch/?partnum=';
//  Test code 83530-0e010

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

        foreach ($html->find('tbody') as $row) {
            $text = $row->findOne('.listing-text-row')->innertext;
            if (!empty($text)) {
                $price = $row->findOne('.listing-price')->innertext;
                $manufacture = $row->findOne('.listing-final-manufacturer')->innertext;
                $elementLink = $row->findOne('.listing-text-row-moreinfo-truck a')->href;

                $parserElement = new ParserElement();
                $parserElement->setText(strip_tags($text))
                    ->setPrice(strip_tags($price))
                    ->setLink($elementLink)
                    ->setManufacture(strip_tags($manufacture));

                $this->elements[] = $parserElement;
            }
        }

        return [
            'url' => $partSearchUrl,
            'elements' => $this->elements
        ];
    }
}
