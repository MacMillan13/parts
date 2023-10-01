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

    #[Route(path: "/example", name: "vue_example_page", methods: ["GET"])]
    public function vueExamplePage(): Response
    {
        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig'));
    }

    #[Route(path: "/iveco/{route}", name: "vue_iveco_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/iveco", name: "vue_iveco_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mini/{route}", name: "vue_mini_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mini", name: "vue_mini_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/skoda/{route}", name: "vue_skoda_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/skoda", name: "vue_skoda_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/jeep/{route}", name: "vue_jeep_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/jeep", name: "vue_jeep_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mercedes/{route}", name: "vue_mercedes_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mercedes", name: "vue_mercedes_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/alfa-romeo/{route}", name: "vue_alfa-romeo_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/alfa-romeo", name: "vue_alfa-romeo_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hyundai/{route}", name: "vue_hyundai_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hyundai", name: "vue_hyundai_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/cadillac/{route}", name: "vue_cadillac_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/cadillac", name: "vue_cadillac_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/smart/{route}", name: "vue_smart_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/smart", name: "vue_smart_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/chery/{route}", name: "vue_chery_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/chery", name: "vue_chery_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/jaguar/{route}", name: "vue_jaguar_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/jaguar", name: "vue_jaguar_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/gmc/{route}", name: "vue_gmc_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/gmc", name: "vue_gmc_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hino-heavy/{route}", name: "vue_hino-heavy_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hino-heavy", name: "vue_hino-heavy_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/land-rover/{route}", name: "vue_land-rover_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/land-rover", name: "vue_land-rover_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mitsubishi/{route}", name: "vue_mitsubishi_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mitsubishi", name: "vue_mitsubishi_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bmw-moto/{route}", name: "vue_bmw-moto_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bmw-moto", name: "vue_bmw-moto_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/infiniti/{route}", name: "vue_infiniti_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/infiniti", name: "vue_infiniti_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/peugeot/{route}", name: "vue_peugeot_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/peugeot", name: "vue_peugeot_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/volvo-trucks/{route}", name: "vue_volvo-trucks_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/volvo-trucks", name: "vue_volvo-trucks_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/nissan/{route}", name: "vue_nissan_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/nissan", name: "vue_nissan_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/rolls-royce/{route}", name: "vue_rolls-royce_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/rolls-royce", name: "vue_rolls-royce_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/kia-korea/{route}", name: "vue_kia-korea_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/kia-korea", name: "vue_kia-korea_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/daf/{route}", name: "vue_daf_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/daf", name: "vue_daf_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/chrysler/{route}", name: "vue_chrysler_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/chrysler", name: "vue_chrysler_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/fiat/{route}", name: "vue_fiat_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/fiat", name: "vue_fiat_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/dodge/{route}", name: "vue_dodge_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/dodge", name: "vue_dodge_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/saab/{route}", name: "vue_saab_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/saab", name: "vue_saab_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/plymouth/{route}", name: "vue_plymouth_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/plymouth", name: "vue_plymouth_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/audi/{route}", name: "vue_audi_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/audi", name: "vue_audi_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/citroen/{route}", name: "vue_citroen_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/citroen", name: "vue_citroen_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/suzuki/{route}", name: "vue_suzuki_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/suzuki", name: "vue_suzuki_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/lexus/{route}", name: "vue_lexus_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/lexus", name: "vue_lexus_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/porsche/{route}", name: "vue_porsche_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/porsche", name: "vue_porsche_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/pontiac/{route}", name: "vue_pontiac_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/pontiac", name: "vue_pontiac_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/scania/{route}", name: "vue_scania_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/scania", name: "vue_scania_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/kia/{route}", name: "vue_kia_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/kia", name: "vue_kia_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/renault/{route}", name: "vue_renault_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/renault", name: "vue_renault_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hino-light/{route}", name: "vue_hino-light_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hino-light", name: "vue_hino-light_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/ford/{route}", name: "vue_ford_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/ford", name: "vue_ford_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/chevrolet/{route}", name: "vue_chevrolet_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/chevrolet", name: "vue_chevrolet_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/daewoo/{route}", name: "vue_daewoo_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/daewoo", name: "vue_daewoo_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/renault-trucks/{route}", name: "vue_renault-trucks_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/renault-trucks", name: "vue_renault-trucks_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/volvo/{route}", name: "vue_volvo_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/volvo", name: "vue_volvo_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/man/{route}", name: "vue_man_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/man", name: "vue_man_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/dacia/{route}", name: "vue_dacia_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/dacia", name: "vue_dacia_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hyundai-korea/{route}", name: "vue_hyundai-korea_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hyundai-korea", name: "vue_hyundai-korea_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/opel/{route}", name: "vue_opel_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/opel", name: "vue_opel_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/vw/{route}", name: "vue_vw_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/vw", name: "vue_vw_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mazda/{route}", name: "vue_mazda_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/mazda", name: "vue_mazda_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/isuzu/{route}", name: "vue_isuzu_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/isuzu", name: "vue_isuzu_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/ssangyong/{route}", name: "vue_ssangyong_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/ssangyong", name: "vue_ssangyong_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bentley/{route}", name: "vue_bentley_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bentley", name: "vue_bentley_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hummer/{route}", name: "vue_hummer_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/hummer", name: "vue_hummer_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/seat/{route}", name: "vue_seat_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/seat", name: "vue_seat_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/subaru/{route}", name: "vue_subaru_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/subaru", name: "vue_subaru_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/toyota/{route}", name: "vue_toyota_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/toyota", name: "vue_toyota_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/honda/{route}", name: "vue_honda_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/honda", name: "vue_honda_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bmw/{route}", name: "vue_bmw_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/bmw", name: "vue_bmw_single_models_page", requirements: ["route" => ".+"], methods: ["GET"])]
    #[Route(path: "/vin/{route}", name: "vue_vin_page", requirements: ["route" => ".+"], methods: ["GET"])]
    public function vueAutoModelPage(Request $request): Response
    {
        $requestUri = $request->getRequestUri();

        $paths = explode('/', $requestUri);

        $brand = $paths[1];

        return new Response($this->templatingEngine->render('@SyliusShop/Homepage/index.html.twig', [
            'metaTitle' => $brand . ' Auto Parts  - FindAutoParts.com',
            'metaDescription' => 'Find ' . $brand , ' auto parts online. Browse different vendors. See auto parts prices. Get the best deal. FindAutoParts.com'
        ]));
    }
}
