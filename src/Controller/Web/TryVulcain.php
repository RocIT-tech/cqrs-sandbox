<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="try_vulcain",
 *     path="/vulcain",
 *     methods={"GET"}
 * )
 */
class TryVulcain
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke()
    {
        return new Response($this->twig->render('try_vulcain.html.twig'));
    }
}
