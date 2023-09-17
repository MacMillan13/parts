<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class PartController extends AbstractController
{
    public function __construct(private Environment $templatingEngine)
    {
    }

    #[Route(path: "/part/{partNumber}", name: "vue_brand_page", methods: ["GET"])]
    public function vueBrandPage($partNumber): Response
    {
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig'));
    }
}
