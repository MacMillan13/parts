<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Entity;

class ParserElement
{
    private $text;

    private $price;

    private $manufacture;

    private $link;

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    public function getPrice(): string
    {
        return $this->text;
    }

    public function getManufacture(): string
    {
        return $this->manufacture;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function setManufacture(string $manufacture): self
    {
        $this->manufacture = $manufacture;

        return $this;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
