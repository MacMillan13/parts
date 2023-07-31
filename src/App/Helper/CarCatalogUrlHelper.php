<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Helper;

class CarCatalogUrlHelper
{
    /**
     * @param array $parametersArray
     * @return string
     */
    public function buildParametersUrl(array $parametersArray): string
    {
        $parametersCriteria = '';

        foreach ($parametersArray as $oneParam) {
            if (!empty($oneParam)) {
                if (strlen($parametersCriteria) === 0) {
                    $parametersCriteria = '&parameter=' . $oneParam;
                } else {
                    $parametersCriteria .= '%' . $oneParam;
                }
            }
        }

        return $parametersCriteria;
    }
}
