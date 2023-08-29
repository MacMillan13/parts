<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

class AutoCatalog
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

    #[MongoDB\Field(type: 'string')]
    protected $sunroof;

    #[MongoDB\Field(type: 'string')]
    protected $navigation;

    #[MongoDB\Field(type: 'string')]
    protected $vsa;

    #[MongoDB\Field(type: 'string')]
    protected $doorCount;

    #[MongoDB\Field(type: 'string')]
    protected $abs;

    #[MongoDB\Field(type: 'string')]
    protected $modificationId;

    #[MongoDB\Field(type: 'string')]
    protected $productPeriod;

    #[MongoDB\Field(type: 'string')]
    protected $engineCapacity;

    #[MongoDB\Field(type: 'string')]
    protected $fuelType;

    #[MongoDB\Field(type: 'string')]
    protected $carName;

    #[MongoDB\Field(type: 'string')]
    protected $specSeries;

    #[MongoDB\Field(type: 'string')]
    protected $bodyCode;

    #[MongoDB\Field(type: 'string')]
    protected $transmission;

    #[MongoDB\Field(type: 'string')]
    protected $grade;

    #[MongoDB\Field(type: 'string')]
    protected $classification;

    #[MongoDB\Field(type: 'string')]
    protected $autoParameters;

    #[MongoDB\Field(type: 'string')]
    protected $specModelDate;

    #[MongoDB\Field(type: 'string')]
    protected $specVinPart;

    #[MongoDB\Field(type: 'string')]
    protected $specModification;

    #[MongoDB\Field(type: 'string')]
    protected $specCatalog;

    #[MongoDB\Field(type: 'string')]
    protected $year;

    #[MongoDB\Field(type: 'string')]
    protected $model;

    /**
     * @param object|null $id
     * @return void
     */
    public function setId(?object $id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     */
    public function setYear($year): AutoCatalog
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model): AutoCatalog
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModif()
    {
        return $this->modif;
    }

    /**
     * @param mixed $modif
     */
    public function setModif($modif): AutoCatalog
    {
        $this->modif = $modif;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCarName()
    {
        return $this->carName;
    }

    /**
     * @param mixed $carName
     */
    public function setCarName($carName): AutoCatalog
    {
        $this->carName = $carName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecSeries()
    {
        return $this->specSeries;
    }

    /**
     * @param mixed $specSeries
     */
    public function setSpecSeries($specSeries): AutoCatalog
    {
        $this->specSeries = $specSeries;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBodyCode()
    {
        return $this->bodyCode;
    }

    /**
     * @param mixed $bodyCode
     */
    public function setBodyCode($bodyCode): AutoCatalog
    {
        $this->bodyCode = $bodyCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * @param mixed $transmission
     */
    public function setTransmission($transmission): AutoCatalog
    {
        $this->transmission = $transmission;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param mixed $grade
     */
    public function setGrade($grade): AutoCatalog
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClassification()
    {
        return $this->classification;
    }

    /**
     * @param mixed $classification
     */
    public function setClassification($classification): AutoCatalog
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoParameters()
    {
        return $this->autoParameters;
    }

    /**
     * @param mixed $autoParameters
     */
    public function setAutoParameters($autoParameters): AutoCatalog
    {
        $this->autoParameters = $autoParameters;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecModelDate()
    {
        return $this->specModelDate;
    }

    /**
     * @param mixed $specModelDate
     */
    public function setSpecModelDate($specModelDate): AutoCatalog
    {
        $this->specModelDate = $specModelDate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecVinPart()
    {
        return $this->specVinPart;
    }

    /**
     * @param mixed $specVinPart
     */
    public function setSpecVinPart($specVinPart): AutoCatalog
    {
        $this->specVinPart = $specVinPart;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecModification()
    {
        return $this->specModification;
    }

    /**
     * @param mixed $specModification
     */
    public function setSpecModification($specModification): AutoCatalog
    {
        $this->specModification = $specModification;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSpecCatalog()
    {
        return $this->specCatalog;
    }

    /**
     * @param mixed $specCatalog
     */
    public function setSpecCatalog($specCatalog): AutoCatalog
    {
        $this->specCatalog = $specCatalog;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSunroof()
    {
        return $this->sunroof;
    }

    /**
     * @param mixed $sunroof
     */
    public function setSunroof($sunroof): AutoCatalog
    {
        $this->sunroof = $sunroof;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    /**
     * @param mixed $navigation
     */
    public function setNavigation($navigation): AutoCatalog
    {
        $this->navigation = $navigation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVsa()
    {
        return $this->vsa;
    }

    /**
     * @param mixed $vsa
     */
    public function setVsa($vsa): AutoCatalog
    {
        $this->vsa = $vsa;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDoorCount()
    {
        return $this->doorCount;
    }

    /**
     * @param mixed $doorCount
     */
    public function setDoorCount($doorCount): AutoCatalog
    {
        $this->doorCount = $doorCount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAbs()
    {
        return $this->abs;
    }

    /**
     * @param mixed $abs
     */
    public function setAbs($abs): AutoCatalog
    {
        $this->abs = $abs;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModificationId()
    {
        return $this->modificationId;
    }

    /**
     * @param mixed $modificationId
     */
    public function setModificationId($modificationId): AutoCatalog
    {
        $this->modificationId = $modificationId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductPeriod()
    {
        return $this->productPeriod;
    }

    /**
     * @param mixed $productPeriod
     */
    public function setProductPeriod($productPeriod): AutoCatalog
    {
        $this->productPeriod = $productPeriod;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEngineCapacity()
    {
        return $this->engineCapacity;
    }

    /**
     * @param mixed $engineCapacity
     */
    public function setEngineCapacity($engineCapacity): AutoCatalog
    {
        $this->engineCapacity = $engineCapacity;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFuelType()
    {
        return $this->fuelType;
    }

    /**
     * @param mixed $fuelType
     */
    public function setFuelType($fuelType): AutoCatalog
    {
        $this->fuelType = $fuelType;

        return $this;
    }

    /**
     * @param object $parameters
     * @return $this
     */
    public function setParameters(object $parameters): AutoCatalog
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param string $modelId
     * @return $this
     */
    public function setModelId(string $modelId): AutoCatalog
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return $this
     */
    public function setCatalogId(string $catalogId): AutoCatalog
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @return $this
     */
    public function setDateTime(): AutoCatalog
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
    public function setYearId(?string $yearId): AutoCatalog
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
    public function setRegionId(?string $regionId): AutoCatalog
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
    public function setSteeringId(?string $steeringId): AutoCatalog
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
    public function setSeriesId(?string $seriesId): AutoCatalog
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
    public function setBodyTypeId(?string $bodyTypeId): AutoCatalog
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
    public function setTransmissionTypeId(?string $transmissionTypeId): AutoCatalog
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
    public function setExactModelId(?string $exactModelId): AutoCatalog
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
    public function setEngineId(?string $engineId): AutoCatalog
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
     * @return AutoCatalog
     */
    public function setCarList($carList): AutoCatalog
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
