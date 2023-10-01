<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Helper;

class ElementCodeHelper
{
    /**
     * @param $name
     * @return string
     */
    public function prepare(string $name): string
    {
        $code = $this->prepareAutoCode($name);

        return str_replace(['.'], [''], $code);
    }

    /**
     * @param string $name
     * @return string
     */
    public function prepareAutoCode(string $name): string
    {
        $trimmedName = strtolower(trim($name));

        $code = preg_replace('!\s+!', ' ', strtolower($trimmedName));

        return str_replace([' - ', ' / ', ' — ', '—', ', ', ' ', '(', ')', ',', '/'], ['-', '-', '-', '-', '-', '_', '', '', '-', '-'], $code);
    }
}
