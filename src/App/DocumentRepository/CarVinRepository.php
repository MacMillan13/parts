<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DocumentRepository;

use BitBag\OpenMarketplace\App\Document\CarVin;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class CarVinRepository extends DocumentRepository
{
    /**
     * @param string $vinCode
     * @return array|null
     * @throws \Exception
     */
    public function getVinDataWithCatalog(string $vinCode): ?array
    {
        $builder = $this->dm->createAggregationBuilder(CarVin::class);
        $builder
            ->match()
                ->field('vinCode')
                ->equals($vinCode)
            ->lookup('CarCatalog')
                ->localField('catalogId')
                ->foreignField('_id')
                ->alias('catalog')
            ->limit(1);

        $dataArray = $builder->getAggregation()->getIterator()->toArray();

        if (!empty($dataArray) && !empty($dataArray[0])) {
            return $dataArray[0];
        }

        return null;
    }
}
