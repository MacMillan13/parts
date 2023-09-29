<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Service;

class NamingService
{
    /**
     * @param $name
     * @return string
     */
    public function prepare($name): string
    {
        $trimmedName = trim($name);

        $code = preg_replace('!\s+!', ' ', strtolower($trimmedName));

        return str_replace([' - ', ' ', '(', ')'], ['-', '_', '', ''], $code);
    }
}
