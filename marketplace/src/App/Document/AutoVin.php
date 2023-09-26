<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use BitBag\OpenMarketplace\App\DocumentRepository\AutoVinRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MongoId;

#[MongoDB\Document(repositoryClass: AutoVinRepository::class)]
class AutoVin
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
    public function setAutoData(object $autoData): AutoVin
    {
        $this->autoData = $autoData;

        return $this;
    }

    /**
     * @param string $vinCode
     * @return $this
     */
    public function setVinCode(string $vinCode): AutoVin
    {
        $this->vinCode = $vinCode;

        return $this;
    }

    /**
     * @return AutoVin
     */
    public function setDateTime(): AutoVin
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
     * @param MongoId $catalogId
     * @return AutoVin
     */
    public function setCatalogId(MongoId $catalogId): AutoVin
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
    public function setExactMatch($exactMatch): AutoVin
    {
        $this->exactMatch = $exactMatch;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVinCode()
    {
        return $this->vinCode;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
