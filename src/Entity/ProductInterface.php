<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Entity;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends BaseProductInterface
{
    public function resetImages(): void;

    public function hasVendor(): bool;

    public function getVendor(): ?VendorInterface;

    public function setVendor(?VendorInterface $vendor): void;

    public function setAttributesFrom(ProductDraftInterface $draft): void;

    public function isDeleted(): bool;

    public function setDeleted(bool $deleted): void;

    public function setChannels(Collection $channels): void;
}
