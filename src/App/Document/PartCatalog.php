<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class PartCatalog
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'raw')]
    protected $catalogData;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'string')]
    protected $carId;

    /**
     * @param object $catalogData
     * @return $this
     */
    public function setCatalogData(object $catalogData): PartCatalog
    {
        $this->catalogData = $catalogData;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return PartCatalog
     */
    public function setCatalogId(string $catalogId): PartCatalog
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param string $carId
     * @return PartCatalog
     */
    public function setCarId(string $carId): PartCatalog
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * @return array
     */
    public function getCatalogData()
    {
        return $this->catalogData;
    }
}
