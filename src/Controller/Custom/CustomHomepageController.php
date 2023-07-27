<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Controller\Custom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

final class CustomHomepageController extends AbstractController
{
    public function __construct(private Environment $templatingEngine)
    {
    }

    #[Route('/', name: 'sylius_shop_homepage')]
    public function indexAction(Request $request): Response
    {
        dd(222);
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig'));
    }
}
