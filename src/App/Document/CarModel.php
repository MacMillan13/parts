<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class CarModel
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'raw')]
    protected $models;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

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
     * @param mixed $catalogId
     */
    public function setCatalogId($catalogId): CarModel
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModels()
    {
        return $this->models;
    }

    /**
     * @param mixed $models
     */
    public function setModels($models): CarModel
    {
        $this->models = $models;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return CarModel
     */
    public function setDateTime(): CarModel
    {
        $this->dateTime = new \DateTime();;

        return $this;
    }

}
