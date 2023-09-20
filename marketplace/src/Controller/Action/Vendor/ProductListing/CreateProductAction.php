<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Controller\Action\Vendor\ProductListing;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ShopUserInterface;
use BitBag\OpenMarketplace\Exception\VendorNotFoundException;
use BitBag\OpenMarketplace\Factory\ProductListingFromDraftFactoryInterface;
use BitBag\OpenMarketplace\Form\ProductListing\ProductType;
use BitBag\OpenMarketplace\Repository\ProductListing\ProductDraftRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\EventDispatcherInterface;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactoryInterface;
use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Twig\Environment;

final class CreateProductAction
{
    private MetadataInterface $metadata;

    private RequestConfigurationFactoryInterface $requestConfigurationFactory;

    private NewResourceFactoryInterface $newResourceFactory;

    private FactoryInterface $factory;

    private ProductListingFromDraftFactoryInterface $productListingFromDraftFactory;

    private RedirectHandlerInterface $redirectHandler;

    private FlashHelperInterface $flashHelper;

    private EventDispatcherInterface $eventDispatcher;

    private ProductDraftRepositoryInterface $productDraftRepository;

    private ImageUploaderInterface $imageUploader;

    private FormFactoryInterface $formFactory;

    private RequestStack $requestStack;

    private RouterInterface $router;

    private Environment $twig;

    private TokenStorageInterface $tokenStorage;

    public function __construct(
        MetadataInterface $metadata,
        RequestConfigurationFactoryInterface $requestConfigurationFactory,
        NewResourceFactoryInterface $newResourceFactory,
        FactoryInterface $factory,
        ProductListingFromDraftFactoryInterface $productListingFromDraftFactory,
        RedirectHandlerInterface $redirectHandler,
        FlashHelperInterface $flashHelper,
        EventDispatcherInterface $eventDispatcher,
        ProductDraftRepositoryInterface $productDraftRepository,
        ImageUploaderInterface $imageUploader,
        FormFactoryInterface $formFactory,
        RequestStack $requestStack,
        RouterInterface $router,
        Environment $twig,
        TokenStorageInterface $tokenStorage,
        ) {
        $this->metadata = $metadata;
        $this->requestConfigurationFactory = $requestConfigurationFactory;
        $this->newResourceFactory = $newResourceFactory;
        $this->factory = $factory;
        $this->productListingFromDraftFactory = $productListingFromDraftFactory;
        $this->redirectHandler = $redirectHandler;
        $this->flashHelper = $flashHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->productDraftRepository = $productDraftRepository;
        $this->imageUploader = $imageUploader;
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->twig = $twig;
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var ProductDraftInterface $newResource */
        $newResource = $this->newResourceFactory->create($configuration, $this->factory);

        $form = $this->formFactory->create(ProductType::class, $newResource);

        $form->handleRequest($request);
        if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
            /** @var ProductDraftInterface $productDraft */
            $productDraft = $form->getData();

            foreach ($newResource->getImages() as $image) {
                $image->setOwner($newResource);
                $this->imageUploader->upload($image);
            }
            foreach ($productDraft->getAttributes() as $attribute) {
                $attribute->setSubject($productDraft);
            }

            $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);

            if ($event->isStopped() && !$configuration->isHtmlRequest()) {
                throw new HttpException($event->getErrorCode(), $event->getMessage());
            }
            if ($event->isStopped()) {
                $this->flashHelper->addFlashFromEvent($configuration, $event);

                $eventResponse = $event->getResponse();
                if (null !== $eventResponse) {
                    return $eventResponse;
                }

                return $this->redirectHandler->redirectToIndex($configuration, $newResource);
            }

            /** @var TokenInterface $token */
            $token = $this->tokenStorage->getToken();
            /** @var ShopUserInterface $user */
            $user = $token->getUser();

            $vendor = $user->getVendor();

            if (null === $vendor) {
                throw new VendorNotFoundException('Vendor not found.');
            }

            $productDraft = $this->productListingFromDraftFactory->createNew($productDraft, $vendor);

            $this->productDraftRepository->save($productDraft);

            /** @var Session $session */
            $session = $this->requestStack->getSession();
            $session->getFlashBag()->add('success', 'open_marketplace.ui.product_listing_created');

            return new RedirectResponse($this->router->generate('open_marketplace_vendor_product_listing_index'));
        }

        return new Response(
            $this->twig->render('Context/Vendor/ProductListing/create.html.twig', [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $newResource,
                $this->metadata->getName() => $newResource,
                'form' => $form->createView(),
            ])
        );
    }
}
