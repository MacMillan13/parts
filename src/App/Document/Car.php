<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Car
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'string')]
    protected $brand;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'string')]
    protected $criteria;

    #[MongoDB\Field(type: 'string')]
    protected $description;

    #[MongoDB\Field(type: 'string')]
    protected $frame;

    #[MongoDB\Field(type: 'string')]
    protected $foreignId;

    #[MongoDB\Field(type: 'string')]
    protected $modelId;

    #[MongoDB\Field(type: 'string')]
    protected $modelName;

    #[MongoDB\Field(type: 'string')]
    protected $name;

    #[MongoDB\Field(type: 'string')]
    protected $vin;

    #[MongoDB\Field(type: 'raw')]
    protected $parameters;

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
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * @param mixed $brand
     */
    public function setBrand($brand): Car
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCatalogId()
    {
        return $this->catalogId;
    }

    /**
     * @param mixed $catalogId
     */
    public function setCatalogId($catalogId): Car
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria): Car
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): Car
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFrame()
    {
        return $this->frame;
    }

    /**
     * @param mixed $frame
     */
    public function setFrame($frame): Car
    {
        $this->frame = $frame;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getForeignId()
    {
        return $this->foreignId;
    }

    /**
     * @param mixed $foreignId
     */
    public function setForeignId($foreignId): Car
    {
        $this->foreignId = $foreignId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param mixed $modelId
     */
    public function setModelId($modelId): Car
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param mixed $modelName
     */
    public function setModelName($modelName): Car
    {
        $this->modelName = $modelName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): Car
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVin()
    {
        return $this->vin;
    }

    /**
     * @param mixed $vin
     */
    public function setVin($vin): Car
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */
    public function setParameters($parameters): Car
    {
        $this->parameters = $parameters;

        return $this;
    }
}
