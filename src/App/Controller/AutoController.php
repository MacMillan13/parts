<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route(path: "/toyota/{route}", name: "vue_toyota_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/honda/{route}", name: "vue_honda_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bmw/{route}", name: "vue_bmw_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    public function vueAutoModelPage(Request $request): Response
    {
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig', [
            'metaTitle' => 'Honda Auto Parts  - FindAutoParts.com',
            'metaDescription' => 'Find Honda auto parts online. Browse different vendors. See auto parts prices. Get the best deal. FindAutoParts.com'
        ]));
    }
}
