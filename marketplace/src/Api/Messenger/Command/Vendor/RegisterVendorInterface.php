<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Api\Messenger\Command\Vendor;

use BitBag\OpenMarketplace\Api\Messenger\Command\ShopUserAwareInterface;
use BitBag\OpenMarketplace\Api\Messenger\Command\VendorSlugAwareInterface;
use BitBag\OpenMarketplace\Entity\VendorAddress;

interface RegisterVendorInterface extends ShopUserAwareInterface, VendorSlugAwareInterface
{
    public function getCompanyName(): string;

    public function getTaxIdentifier(): string;

    public function getPhoneNumber(): string;

    public function getDescription(): string;

    public function getVendorAddress(): VendorAddress;
}
