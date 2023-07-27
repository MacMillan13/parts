<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingPriceInterface;
use Sylius\Component\Core\Model\ChannelPricing;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ChannelPricingFactory implements ChannelPricingFactoryInterface
{
    public function createNew(): ChannelPricing
    {
        return new ChannelPricing();
    }

    public function create(
        ?ProductVariantInterface $productVariant,
        ?string $channelCode,
        ?int $price,
        ?int $originalPrice,
        ?int $minimumPrice
    ): ChannelPricing {
        $channelPricing = new ChannelPricing();

        $channelPricing->setProductVariant($productVariant);
        $channelPricing->setChannelCode($channelCode);
        $channelPricing->setPrice($price);
        $channelPricing->setOriginalPrice($originalPrice);
        $channelPricing->setMinimumPrice($minimumPrice);

        return $channelPricing;
    }

    public function createFromProductListingPrice(ProductVariantInterface $productVariant, ProductListingPriceInterface $productListingPrice): ChannelPricing
    {
        $channelPricing = $this->create(
            $productVariant,
            $productListingPrice->getChannelCode(),
            $productListingPrice->getPrice(),
            $productListingPrice->getOriginalPrice(),
            $productListingPrice->getMinimumPrice(),
        );

        $productVariant->addChannelPricing($channelPricing);

        return $channelPricing;
    }
}
