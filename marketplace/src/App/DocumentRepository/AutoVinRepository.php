<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DocumentRepository;

use BitBag\OpenMarketplace\App\Document\AutoVin;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AutoVinRepository extends DocumentRepository
{
    /**
     * @param string $vinCode
     * @return array|null
     * @throws \Exception
     */
    public function getVinDataWithCatalog(string $vinCode): ?array
    {
        $builder = $this->dm->createAggregationBuilder(AutoVin::class);
        $builder
            ->match()
                ->field('vinCode')
                ->equals($vinCode)
            ->lookup('AutoCatalog')
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
