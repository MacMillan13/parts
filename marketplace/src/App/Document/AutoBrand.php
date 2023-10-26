<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Document;

use BitBag\OpenMarketplace\App\DocumentRepository\AutoBrandRepository;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document(repositoryClass: AutoBrandRepository::class)]
class AutoBrand
{
    #[MongoDB\Id]
    protected $id;

    #[MongoDB\Field(type: 'string')]
    protected $catalogId;

    #[MongoDB\Field(type: 'string')]
    protected $name;

    #[MongoDB\Field(type: 'integer')]
    protected $modelsCount;

    #[MongoDB\Field(type: 'integer')]
    protected $priority;

    #[MongoDB\Field(type: 'date')]
    protected $dateTime;

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param mixed $priority
     */
    public function setPriority($priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getCatalogId(): string
    {
        return $this->catalogId;
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
     * @param mixed $catalogId
     */
    public function setCatalogId($catalogId): self
    {
        $this->catalogId = $catalogId;

        return $this;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $modelsCount
     */
    public function setModelsCount($modelsCount): self
    {
        $this->modelsCount = $modelsCount;

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
    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function setDateTime(): AutoBrand
    {
        $this->dateTime = new \DateTime();

        return $this;
    }
}
