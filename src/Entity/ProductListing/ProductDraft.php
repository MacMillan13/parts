<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Entity\ProductListing;

use BitBag\OpenMarketplace\Entity\VendorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Attribute\Model\AttributeValueInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;

class ProductDraft implements ResourceInterface, ProductDraftInterface
{
    protected ?int $id = null;

    protected ?UuidInterface $uuid = null;

    protected string $code;

    protected bool $shippingRequired = false;

    protected ?ShippingCategoryInterface $shippingCategory = null;

    protected bool $isVerified;

    protected string $status;

    protected ?\DateTimeInterface $verifiedAt;

    protected ?\DateTimeInterface $publishedAt;

    protected \DateTimeInterface $createdAt;

    protected int $versionNumber;

    /** @var Collection<int|string, ImageInterface> */
    protected Collection $images;

    /** @var Collection<int|string, ProductTranslationInterface> */
    protected Collection $translations;

    /** @var Collection<int|string, ProductListingPriceInterface> */
    protected Collection $productListingPrices;

    protected ProductListingInterface $productListing;

    /** @var Collection<int, AttributeValueInterface> */
    protected Collection $attributes;

    protected TaxonInterface|null $mainTaxon;

    /** @var Collection<array-key, ProductDraftTaxonInterface> */
    protected Collection $productDraftTaxons;

    /** @var Collection<array-key, ChannelInterface> */
    protected Collection $channels;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->code = '';
        $this->status = ProductDraftInterface::STATUS_CREATED;
        $this->productListingPrices = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->isVerified = false;
        $this->createdAt = new \DateTime();
        $this->versionNumber = 1;
        $this->attributes = new ArrayCollection();
        $this->mainTaxon = null;
        $this->productDraftTaxons = new ArrayCollection();
        $this->channels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): ?UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(?UuidInterface $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function isShippingRequired(): bool
    {
        return $this->shippingRequired;
    }

    public function setShippingRequired(bool $shippingRequired): void
    {
        $this->shippingRequired = $shippingRequired;
    }

    public function getShippingCategory(): ?ShippingCategoryInterface
    {
        return $this->shippingCategory;
    }

    public function setShippingCategory(?ShippingCategoryInterface $shippingCategory): void
    {
        $this->shippingCategory = $shippingCategory;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
    }

    public function getVerifiedAt(): ?\DateTimeInterface
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeInterface $verifiedAt): void
    {
        $this->verifiedAt = $verifiedAt;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getVersionNumber(): int
    {
        return $this->versionNumber;
    }

    public function setVersionNumber(int $versionNumber): void
    {
        $this->versionNumber = $versionNumber;
    }

    /** @return Collection<int|string, ProductTranslationInterface> */
    public function getTranslations(): Collection
    {
        return $this->translations;
    }

    /** @param Collection<int|string, ProductTranslationInterface> $translations */
    public function setTranslations(Collection $translations): void
    {
        $this->translations = $translations;
    }

    public function addTranslation(ProductTranslationInterface $translation): void
    {
        $this->translations->add($translation);
    }

    public function removeTranslation(ProductTranslationInterface $translation): void
    {
        $this->translations->removeElement($translation);
    }

    public function getProductListingPrices(): Collection
    {
        return $this->productListingPrices;
    }

    public function addProductListingPrice(ProductListingPriceInterface $productListingPrice): void
    {
        $productListingPrice->setProductDraft($this);
        $this->productListingPrices->add($productListingPrice);
    }

    public function removeProductListingPrice(ProductListingPriceInterface $productListingPrice): void
    {
        $this->productListingPrices->removeElement($productListingPrice);
    }

    public function getVendor(): VendorInterface
    {
        return $this->getProductListing()->getVendor();
    }

    public function getProductListing(): ProductListingInterface
    {
        return $this->productListing;
    }

    public function setProductListing(ProductListingInterface $productListing): void
    {
        $this->productListing = $productListing;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function incrementVersion(): void
    {
        ++$this->versionNumber;
    }

    public function addTranslationWithKey(ProductTranslationInterface $translation, string $key): void
    {
        $this->translations->set($key, $translation);
    }

    public function addProductListingPriceWithKey(ProductListingPriceInterface $productListingPrice, string $key): void
    {
        $this->productListingPrices->set($key, $productListingPrice);
    }

    public function accept(): void
    {
        $this->setStatus(ProductDraftInterface::STATUS_VERIFIED);
        $this->setVerifiedAt((new \DateTime()));
        $this->setIsVerified(true);
    }

    public function reject(): void
    {
        $this->setStatus(ProductDraftInterface::STATUS_REJECTED);
        $this->setVerifiedAt((new \DateTime()));
    }

    public function sendToVerification(): void
    {
        $this->setStatus(ProductDraftInterface::STATUS_UNDER_VERIFICATION);
        $this->setPublishedAt((new \DateTime()));
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setImages(Collection $images): void
    {
        $this->images = $images;
    }

    public function addImage(ImageInterface $image): void
    {
        $this->images->add($image);
    }

    public function removeImage(ImageInterface $image): void
    {
        $this->images->removeElement($image);
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

    public function getAttributesByLocale(
        string $localeCode,
        string $fallbackLocaleCode,
        ?string $baseLocaleCode = null
    ): Collection {
        if (null === $baseLocaleCode || $baseLocaleCode === $fallbackLocaleCode) {
            $baseLocaleCode = $fallbackLocaleCode;
            $fallbackLocaleCode = null;
        }

        $attributes = $this->attributes->filter(
            function (AttributeValueInterface $attribute) use ($baseLocaleCode) {
                return $attribute->getLocaleCode() === $baseLocaleCode || null === $attribute->getLocaleCode();
            }
        );

        $attributesWithFallback = [];

        /** @var DraftAttributeValueInterface $attribute */
        foreach ($attributes as $attribute) {
            $attributesWithFallback[] = $this->getAttributeInDifferentLocale($attribute, $localeCode, $fallbackLocaleCode);
        }

        /** @var Collection<int, AttributeValueInterface> $collection */
        $collection = new ArrayCollection($attributesWithFallback);

        return $collection;
    }

    public function addAttribute(AttributeValueInterface $attribute): void
    {
        if ($this->hasAttribute($attribute)) {
            return;
        }

        if ($attribute instanceof DraftAttributeValueInterface) {
            $attribute->setDraft($this);
            $this->attributes->add($attribute);
        }
    }

    /** @param DraftAttributeValueInterface $attribute */
    public function removeAttribute(AttributeValueInterface $attribute): void
    {
        if (!$this->hasAttribute($attribute)) {
            return;
        }

        $this->attributes->removeElement($attribute);
        $attribute->setDraft(null);
    }

    public function hasAttribute(AttributeValueInterface $attribute): bool
    {
        return $this->attributes->contains($attribute);
    }

    public function hasAttributeByCodeAndLocale(string $attributeCode, ?string $localeCode = null): bool
    {
        foreach ($this->attributes as $attribute) {
            if (null === $attribute->getAttribute()) {
                continue;
            }
            $actualAttributeCode = $attribute->getAttribute()->getCode();
            $actualLocaleCode = $attribute->getLocaleCode();
            if ($actualAttributeCode === $attributeCode
                && ($actualLocaleCode === $localeCode || null === $attribute->getLocaleCode())) {
                return true;
            }
        }

        return false;
    }

    public function getAttributeByCodeAndLocale(string $attributeCode, ?string $localeCode = null): ?AttributeValueInterface
    {
        foreach ($this->attributes as $attribute) {
            if (null === $attribute->getAttribute()) {
                continue;
            }
            $actualAttributeCode = $attribute->getAttribute()->getCode();
            $actualLocaleCode = $attribute->getLocaleCode();
            if ($actualAttributeCode === $attributeCode &&
                ($actualLocaleCode === $localeCode || null === $actualLocaleCode)) {
                return $attribute;
            }
        }

        return null;
    }

    protected function getAttributeInDifferentLocale(
        DraftAttributeValueInterface $attributeValue,
        string $localeCode,
        ?string $fallbackLocaleCode = null
    ): ?AttributeValueInterface {
        $attributeCode = $attributeValue->getCode();

        if (null === $attributeCode) {
            return null;
        }
        if (!$this->hasNotEmptyAttributeByCodeAndLocale($attributeCode, $localeCode)) {
            return $attributeValue;
        }

        if (
            null !== $fallbackLocaleCode &&
            $this->hasNotEmptyAttributeByCodeAndLocale($attributeCode, $fallbackLocaleCode)
        ) {
            return $this->getAttributeByCodeAndLocale($attributeCode, $fallbackLocaleCode);
        }

        /** @var AttributeValueInterface $attribute */
        $attribute = $this->getAttributeByCodeAndLocale($attributeCode, $localeCode);

        return $attribute;
    }

    protected function hasNotEmptyAttributeByCodeAndLocale(string $attributeCode, string $localeCode): bool
    {
        $attributeValue = $this->getAttributeByCodeAndLocale($attributeCode, $localeCode);
        if (null === $attributeValue) {
            return false;
        }

        $value = $attributeValue->getValue();
        if ('' === $value || null === $value || [] === $value) {
            return false;
        }

        return true;
    }

    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }

    public function setAttributesFrom(ProductDraftInterface $otherDraft): void
    {
        $this->attributes = $otherDraft->getAttributes();
        foreach ($otherDraft->getAttributes() as $attribute) {
            $attribute->setSubject($this);
        }
    }

    /** @return Collection<array-key, ProductDraftTaxonInterface> */
    public function getProductDraftTaxons(): Collection
    {
        return $this->productDraftTaxons;
    }

    public function setProductTaxonsFrom(ProductDraftInterface $otherDraft): void
    {
        $this->productDraftTaxons = $otherDraft->getProductDraftTaxons();
    }

    public function addProductDraftTaxon(ProductDraftTaxonInterface $productDraftTaxons): void
    {
        if (!$this->hasProductDraftTaxon($productDraftTaxons)) {
            $this->productDraftTaxons->add($productDraftTaxons);
            $productDraftTaxons->setProductDraft($this);
        }
    }

    public function removeProductDraftTaxon(ProductDraftTaxonInterface $productDraftTaxons): void
    {
        if ($this->hasProductDraftTaxon($productDraftTaxons)) {
            $this->productDraftTaxons->removeElement($productDraftTaxons);
        }
    }

    public function hasProductDraftTaxon(ProductDraftTaxonInterface $productDraftTaxons): bool
    {
        return $this->productDraftTaxons->contains($productDraftTaxons);
    }

    /** @return Collection<array-key, ?TaxonInterface> */
    public function getTaxons(): Collection
    {
        return $this->productDraftTaxons->map(function (ProductDraftTaxonInterface $productDraftTaxons): ?TaxonInterface {
            return $productDraftTaxons->getTaxon();
        });
    }

    public function hasTaxon(TaxonInterface $taxon): bool
    {
        return $this->getTaxons()->contains($taxon);
    }

    public function getMainTaxon(): TaxonInterface|null
    {
        return $this->mainTaxon;
    }

    public function setMainTaxon(?TaxonInterface $mainTaxon): void
    {
        $this->mainTaxon = $mainTaxon;
    }

    public function getTranslationByLocale(string $locale): ?ProductTranslationInterface
    {
        foreach ($this->getTranslations() as $translation) {
            if ($translation->getLocale() == $locale) {
                return $translation;
            }
        }

        return null;
    }

    public function getName(string $locale): ?string
    {
        $translation = $this->getTranslationByLocale($locale);

        return $translation?->getName();
    }

    public function getSlug(string $locale): ?string
    {
        $translation = $this->getTranslationByLocale($locale);

        return $translation?->getSlug();
    }

    public function getAnyTranslationName(): ?string
    {
        foreach ($this->translations as $translation) {
            if (null !== $translation->getName()) {
                return $translation->getName();
            }
        }

        return null;
    }

    public function getProductListingPriceForChannel(ChannelInterface $channel): ?ProductListingPriceInterface
    {
        if (null !== $channel->getCode()) {
            if ($this->productListingPrices->containsKey($channel->getCode())) {
                return $this->productListingPrices->get($channel->getCode());
            }
        }

        return null;
    }

    /** @return Collection<array-key, ChannelInterface> */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /** @param Collection<array-key, ChannelInterface> $channels */
    public function setChannels(Collection $channels): void
    {
        $this->channels = $channels;
    }

    public function addChannel(ChannelInterface $channel): void
    {
        $this->channels->add($channel);
    }

    public function removeChannel(ChannelInterface $channel): void
    {
        $this->channels->removeElement($channel);
    }
}
