<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Controller\Custom;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class CustomHomepageController extends AbstractController
{
    public function __construct(private Environment $templatingEngine)
    {
    }

    public function indexAction(Request $request): Response
    {
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig'));
    }
}
