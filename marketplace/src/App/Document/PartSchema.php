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
    protected $group;

    #[MongoDB\Field(type: 'string')]
    protected $criteria;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param mixed $group
     */
    public function setGroup($group): self
    {
        $this->group = $group;

        return $this;
    }

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

    /**
     * @return mixed
     */
    public function getPartSchemaData()
    {
        return $this->partSchemaData;
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
