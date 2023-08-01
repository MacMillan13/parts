<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class CarVin
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'raw')]
    protected $autoData;

    #[MongoDB\Field(type: 'string')]
    protected $vinCode;

    #[MongoDB\Field(type: 'id')]
    protected $catalogId;

    #[MongoDB\Field(type: 'bool')]
    protected $exactMatch;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @param object $autoData
     * @return $this
     */
    public function setAutoData(object $autoData): CarVin
    {
        $this->autoData = $autoData;

        return $this;
    }

    /**
     * @param string $vinCode
     * @return $this
     */
    public function setVinCode(string $vinCode): CarVin
    {
        $this->vinCode = $vinCode;

        return $this;
    }

    /**
     * @return CarVin
     */
    public function setDateTime(): CarVin
    {
        $this->dateTime = new \DateTime();

        return $this;
    }

    /**
     * @return object
     */
    public function getAutoData(): object
    {
        return (object)$this->autoData;
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
    public function setCatalogId($catalogId): CarVin
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExactMatch()
    {
        return $this->exactMatch;
    }

    /**
     * @param mixed $exactMatch
     */
    public function setExactMatch($exactMatch): CarVin
    {
        $this->exactMatch = $exactMatch;

        return $this;
    }
}
