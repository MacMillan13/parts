<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Helper;

class ArrayHelper
{
    /**
     * @param array $array
     * @param $key
     * @return array
     */
    public function arrayUniqueByKey(array $array, $key): array
    {
        $tmp = $key_array = array();
        $i = 0;

        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i++;
        }

        return $tmp;
    }
}
