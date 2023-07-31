<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class PartCatalogGroup
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'raw')]
    protected $catalogData;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'string')]
    protected $carId;

    #[MongoDB\Field(type: 'string')]
    protected $groupId;

    #[MongoDB\Field(type: 'string')]
    protected $criteria;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @param object $catalogData
     * @return $this
     */
    public function setCatalogData(object $catalogData): PartCatalogGroup
    {
        $this->catalogData = $catalogData;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return PartCatalogGroup
     */
    public function setCatalogId(string $catalogId): PartCatalogGroup
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param string $criteria
     * @return PartCatalogGroup
     */
    public function setCriteria(string $criteria): PartCatalogGroup
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @param string $carId
     * @return PartCatalogGroup
     */
    public function setCarId(string $carId): PartCatalogGroup
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * @param string $groupId
     * @return PartCatalogGroup
     */
    public function setGroupId(string $groupId): PartCatalogGroup
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDateTime(): PartCatalogGroup
    {
        $this->dateTime = new \DateTime();

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
