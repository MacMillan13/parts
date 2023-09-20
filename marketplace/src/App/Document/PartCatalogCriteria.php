<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class PartCatalogCriteria
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
    protected $criteria;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @param object $catalogData
     * @return $this
     */
    public function setCatalogData(object $catalogData): PartCatalogCriteria
    {
        $this->catalogData = $catalogData;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return PartCatalogCriteria
     */
    public function setCatalogId(string $catalogId): PartCatalogCriteria
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param string $criteria
     * @return PartCatalogCriteria
     */
    public function setCriteria(string $criteria): PartCatalogCriteria
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @param string $carId
     * @return PartCatalogCriteria
     */
    public function setCarId(string $carId): PartCatalogCriteria
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * @return PartCatalogCriteriaGroup
     */
    public function setDateTime(): PartCatalogCriteria
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
