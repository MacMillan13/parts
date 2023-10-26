<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\DocumentRepository;

use BitBag\OpenMarketplace\App\Document\AutoBrand;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class AutoBrandRepository extends DocumentRepository
{
    /**
     * @return array
     * @throws \Exception
     */
    public function findAllBrandsOrderByPriority(): array
    {
        $builder = $this->dm->createAggregationBuilder(AutoBrand::class);

        $builder
            ->match()
                ->field('priority')
                ->notEqual(0)
            ->sort('priority', 'desc');

        return $builder->getAggregation()->getIterator()->toArray();
    }
}
