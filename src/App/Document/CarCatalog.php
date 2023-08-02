<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class CarCatalog
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'string')]
    protected $modelId;

    #[MongoDB\Field(type: 'string')]
    protected $yearId;

    #[MongoDB\Field(type: 'string')]
    protected $regionId;

    #[MongoDB\Field(type: 'string')]
    protected $steeringId;

    #[MongoDB\Field(type: 'string')]
    protected $seriesId;

    #[MongoDB\Field(type: 'string')]
    protected $bodyTypeId;

    #[MongoDB\Field(type: 'string')]
    protected $transmissionTypeId;

    #[MongoDB\Field(type: 'string')]
    protected $exactModelId;

    #[MongoDB\Field(type: 'string')]
    protected $engineId;

    #[MongoDB\Field(type: 'raw')]
    protected $parameters;

    #[MongoDB\Field(type: 'raw')]
    protected $carList;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @param object|null $id
     * @return void
     */
    public function setId(?object $id): void
    {
        $this->id = $id;
    }

    /**
     * @param object $parameters
     * @return $this
     */
    public function setParameters(object $parameters): CarCatalog
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $modelId
     * @return $this
     */
    public function setModelId(string $modelId): CarCatalog
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return $this
     */
    public function setCatalogId(string $catalogId): CarCatalog
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDateTime(): CarCatalog
    {
        $this->dateTime = new \DateTime();

        return $this;
    }

    /**
     * @return object
     */
    public function getParameters(): object
    {
        return (object)$this->parameters;
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
    public function getYearId()
    {
        return $this->yearId;
    }

    /**
     * @param mixed $yearId
     */
    public function setYearId(?string $yearId): CarCatalog
    {
        $this->yearId = $yearId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegionId()
    {
        return $this->regionId;
    }

    /**
     * @param mixed $regionId
     */
    public function setRegionId(?string $regionId): CarCatalog
    {
        $this->regionId = $regionId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSteeringId()
    {
        return $this->steeringId;
    }

    /**
     * @param mixed $steeringId
     */
    public function setSteeringId(?string $steeringId): CarCatalog
    {
        $this->steeringId = $steeringId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeriesId()
    {
        return $this->seriesId;
    }

    /**
     * @param mixed $seriesId
     */
    public function setSeriesId(?string $seriesId): CarCatalog
    {
        $this->seriesId = $seriesId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBodyTypeId()
    {
        return $this->bodyTypeId;
    }

    /**
     * @param mixed $bodyTypeId
     */
    public function setBodyTypeId(?string $bodyTypeId): CarCatalog
    {
        $this->bodyTypeId = $bodyTypeId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransmissionTypeId()
    {
        return $this->transmissionTypeId;
    }

    /**
     * @param mixed $transmissionTypeId
     */
    public function setTransmissionTypeId(?string $transmissionTypeId): CarCatalog
    {
        $this->transmissionTypeId = $transmissionTypeId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExactModelId()
    {
        return $this->exactModelId;
    }

    /**
     * @param mixed $exactModelId
     */
    public function setExactModelId(?string $exactModelId): CarCatalog
    {
        $this->exactModelId = $exactModelId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEngineId()
    {
        return $this->engineId;
    }

    /**
     * @param mixed $engineId
     */
    public function setEngineId(?string $engineId): CarCatalog
    {
        $this->engineId = $engineId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarList()
    {
        return $this->carList;
    }

    /**
     * @param $carList
     * @return CarCatalog
     */
    public function setCarList($carList): CarCatalog
    {
        $this->carList = $carList;

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
     * @return mixed
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

}
