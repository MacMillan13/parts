<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Parser;

use voku\helper\HtmlDomParser;

class RockAutoParser extends AbstractParser
{
    private const ROCK_AUTO_SEARCH = 'https://www.rockauto.com/en/partsearch/?partnum=';

    public function execute(): array
    {
        $html = HtmlDomParser::file_get_html(self::ROCK_AUTO_SEARCH);

        foreach ($html->find('.listing-price') as $row) {
            dd(strip_tags($row->innertext));
        }
    }
}
