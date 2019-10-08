<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Command\CreateChallengerCommand;
use App\Command\CreateChallengerCommandHandler;
use App\Form\AddChallengerType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route(
 *     name="index",
 *     path="/",
 *     methods={"GET", "POST"}
 * )
 */
class AddChallenger
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var CreateChallengerCommandHandler
     */
    private $createChallengerCommandHandler;

    public function __construct(
        FormFactoryInterface $formFactory,
        Environment $twig,
        CreateChallengerCommandHandler $createChallengerCommandHandler
    ) {
        $this->formFactory = $formFactory;
        $this->twig        = $twig;
        $this->createChallengerCommandHandler = $createChallengerCommandHandler;
    }

    public function __invoke(Request $request)
    {
        $command = new CreateChallengerCommand();

        $form = $this->formFactory->create(AddChallengerType::class, $command, [
            'validation_groups' => ['form'],
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command->id = Uuid::uuid4()->toString();
            ($this->createChallengerCommandHandler)($command);
        }

        $content = $this->twig->render('add_challenger.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }
}
