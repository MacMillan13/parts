<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Provider;

use BitBag\OpenMarketplace\Entity\ShopUserInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Exception\ShopUserHasNoVendorContextException;
use BitBag\OpenMarketplace\Exception\ShopUserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class VendorProvider implements VendorProviderInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function provideCurrentVendor(): VendorInterface
    {
        /** @var ShopUserInterface|UserInterface|null $user */
        $user = $this->security->getUser();
        if (false === $user instanceof ShopUserInterface) {
            throw new ShopUserNotFoundException();
        }

        /** @var VendorInterface|null $vendor */
        $vendor = $user->getVendor();

        if (null === $vendor) {
            throw new ShopUserHasNoVendorContextException();
        }

        return $vendor;
    }
}
