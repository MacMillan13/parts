<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Part
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'raw')]
    protected $partData;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'string')]
    protected $carId;

    #[MongoDB\Field(type: 'string')]
    protected $groupId;

    /**
     * @param object $partData
     * @return $this
     */
    public function setPartData(object $partData): Part
    {
        $this->partData = $partData;

        return $this;
    }

    /**
     * @param string $catalogId
     * @return Part
     */
    public function setCatalogId(string $catalogId): Part
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param string $carId
     * @return Part
     */
    public function setCarId(string $carId): Part
    {
        $this->carId = $carId;

        return $this;
    }

    /**
     * @param string $groupId
     * @return Part
     */
    public function setGroupId(string $groupId): Part
    {
        $this->groupId = $groupId;

        return $this;
    }

    public function getPartData()
    {
        return $this->partData;
    }
}
