<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class CarBrand
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'string')]
    protected $carId;

    #[MongoDB\Field(type: 'string')]
    protected $name;

    #[MongoDB\Field(type: 'integer')]
    protected $modelsCount;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    public function getCarId(): string
    {
        return $this->carId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModelsCount(): int
    {
        return $this->modelsCount;
    }

    /**
     * @param mixed $carId
     */
    public function setCarId($carId): void
    {
        $this->carId = $carId;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @param mixed $modelsCount
     */
    public function setModelsCount($modelsCount): void
    {
        $this->modelsCount = $modelsCount;
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
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function setDateTime(): CarBrand
    {
        $this->dateTime = new \DateTime();

        return $this;
    }
}
