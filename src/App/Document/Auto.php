<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Auto
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'raw')]
    protected $autoData;

    #[MongoDB\Field(type: 'string')]
    protected $vinCode;

    /**
     * @param object $autoData
     * @return $this
     */
    public function setAutoData(object $autoData): Auto
    {
        $this->autoData = $autoData;

        return $this;
    }

    /**
     * @param string $vinCode
     * @return $this
     */
    public function setVinCode(string $vinCode): Auto
    {
        $this->vinCode = $vinCode;

        return $this;
    }

    /**
     * @return array
     */
    public function getAutoData(): array
    {
        return $this->autoData;
    }
}
