<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class AutoController extends AbstractController
{
    /**
     * @param Environment $templatingEngine
     */
    public function __construct(private Environment $templatingEngine)
    {
    }

    #[Route(path: "/{brand}", name: "vue_brand_page", methods: ["GET"])]
    public function vueBrandPage($brand): Response
    {
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig'));
    }

    #[Route(path: "/honda/{route}", name: "vue_auto_model_page", requirements: ["route" => ".+"], methods: ["GET"])]
    public function vueAutoModelPage(): Response
    {
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig'));
    }
}
