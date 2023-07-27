<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface VendorProfileInterface extends ResourceInterface
{
    public function getId(): ?int;

    public function setId(int $id): void;

    public function getCompanyName(): ?string;

    public function setCompanyName(?string $companyName): void;

    public function getTaxIdentifier(): ?string;

    public function setTaxIdentifier(?string $taxIdentifier): void;

    public function getPhoneNumber(): ?string;

    public function setPhoneNumber(?string $phoneNumber): void;

    public function getVendorAddress(): ?VendorAddressInterface;

    public function setVendorAddress(?VendorAddressInterface $vendorAddress): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;

    public function getImage(): ?VendorImageInterface;

    public function getBackgroundImage(): ?VendorBackgroundImageInterface;
}
