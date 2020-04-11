<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route(
 *     name="homepage",
 *     path="/",
 *     methods={"GET"}
 * )
 */
class Homepage
{
    private const TEMPLATE_MAPPING = [
        'react' => 'homepage/homepage_react.html.twig',
        'vue'   => 'homepage/homepage_vue.html.twig',
    ];

    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(Request $request)
    {
        return new Response($this->twig->render(
            self::TEMPLATE_MAPPING[$request->query->get('front')] ?? 'homepage/homepage.html.twig'
        ));
    }
}
