<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class PartCatalogCriteriaGroup
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
    public function setCatalogData(object $catalogData): PartCatalogCriteriaGroup
    {
        $this->catalogData = $catalogData;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return PartCatalogCriteriaGroup
     */
    public function setCatalogId(string $catalogId): PartCatalogCriteriaGroup
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param string $criteria
     * @return PartCatalogCriteriaGroup
     */
    public function setCriteria(string $criteria): PartCatalogCriteriaGroup
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @param string $carId
     * @return PartCatalogCriteriaGroup
     */
    public function setCarId(string $carId): PartCatalogCriteriaGroup
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * @param string $groupId
     * @return PartCatalogCriteriaGroup
     */
    public function setGroupId(string $groupId): PartCatalogCriteriaGroup
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDateTime(): PartCatalogCriteriaGroup
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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCatalogId()
    {
        return $this->catalogId;
    }

    /**
     * @return mixed
     */
    public function getCarId()
    {
        return $this->carId;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
