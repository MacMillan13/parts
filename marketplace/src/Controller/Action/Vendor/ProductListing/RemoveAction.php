<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Controller\Action\Vendor\ProductListing;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingInterface;
use BitBag\OpenMarketplace\Repository\ProductListing\ProductListingRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

final class RemoveAction
{
    private ProductListingRepositoryInterface $productListingRepository;

    private RouterInterface $router;

    private EntityManagerInterface $entityManager;

    private FlashBagInterface $flashBag;

    public function __construct(
        ProductListingRepositoryInterface $productListingRepository,
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        FlashBag $flashBag
    ) {
        $this->productListingRepository = $productListingRepository;
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
    }

    public function __invoke(Request $request): RedirectResponse
    {
        /** @var ProductListingInterface $productListing */
        $productListing = $this->productListingRepository->find($request->attributes->get('id'));

        $newStatus = true;
        $productListing->setRemoved($newStatus);

        $product = $productListing->getProduct();

        if ($product) {
            $product->setEnabled(false);
            $this->entityManager->persist($product);
        }

        $this->entityManager->persist($productListing);
        $this->entityManager->flush();
        $this->flashBag->set('success', 'open_marketplace.ui.removed');

        return new RedirectResponse($this->router->generate('open_marketplace_vendor_product_listing_index'));
    }
}
