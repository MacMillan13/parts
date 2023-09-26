<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Api\Provider;

use BitBag\OpenMarketplace\Entity\ShopUserInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class VendorProvider implements VendorProviderInterface
{
    private FactoryInterface $vendorFactory;

    public function __construct(FactoryInterface $vendorFactory)
    {
        $this->vendorFactory = $vendorFactory;
    }

    public function provide(ShopUserInterface $shopUser): VendorInterface
    {
        if (null === $vendor = $shopUser->getVendor()) {
            /** @var VendorInterface $vendor */
            $vendor = $this->vendorFactory->createNew();
            $vendor->setShopUser($shopUser);
        }

        return $vendor;
    }
}
