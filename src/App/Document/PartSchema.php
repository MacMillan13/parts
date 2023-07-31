<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class PartSchema
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'raw')]
    protected $partSchemaData;

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
     * @param object $partData
     * @return $this
     */
    public function setPartSchemaData(object $partData): PartSchema
    {
        $this->partSchemaData = $partData;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return PartSchema
     */
    public function setCatalogId(string $catalogId): PartSchema
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param string $carId
     * @return PartSchema
     */
    public function setCarId(string $carId): PartSchema
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * @param string $criteria
     * @return PartSchema
     */
    public function setCriteria(string $criteria): PartSchema
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @param string $groupId
     * @return PartSchema
     */
    public function setGroupId(string $groupId): PartSchema
    {
        $this->groupId = $groupId;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDateTime(): PartSchema
    {
        $this->dateTime = new \DateTime();

        return $this;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return object
     */
    public function getPartData()
    {
        return $this->partSchemaData;
    }
}
