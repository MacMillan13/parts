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
}
