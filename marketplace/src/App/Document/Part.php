<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MongoId;

#[MongoDB\Document]
class Part
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'string')]
    protected $partNumber;

    #[MongoDB\Field(type: 'string')]
    protected $name;

    #[MongoDB\Field(type: 'string')]
    protected $notice;

    #[MongoDB\Field(type: 'string')]
    protected $description;

    #[MongoDB\Field(type: 'collection')]
    protected $cross;

    #[MongoDB\Field(type: 'id')]
    protected $partSchemaId;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @param string $partNumber
     * @return Part
     */
    public function setPartNumber(string $partNumber): Part
    {
        $this->partNumber = $partNumber;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Part
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string|null $notice
     * @return $this
     */
    public function setNotice(?string $notice): Part
    {
        $this->notice = $notice;

        return $this;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): Part
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Part
     */
    public function setDateTime(): Part
    {
        $this->dateTime = new \DateTime();

        return $this;
    }

    /**
     * @param MongoId $partSchemaId
     * @return $this
     */
    public function setPartSchemaId(MongoId $partSchemaId): Part
    {
        $this->partSchemaId = $partSchemaId;

        return $this;
    }

    /**
     * @param string $partNumber
     * @return $this
     */
    public function setCross(string $partNumber): Part
    {
        $this->cross[] = $partNumber;

        return $this;
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
    public function getPartNumber()
    {
        return $this->partNumber;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getNotice()
    {
        return $this->notice;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getCross()
    {
        return $this->cross;
    }

    /**
     * @return mixed
     */
    public function getPartSchemaId()
    {
        return $this->partSchemaId;
    }

    /**
     * @return mixed
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
