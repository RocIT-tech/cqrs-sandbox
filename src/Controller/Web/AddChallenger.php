<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Command\CreateChallengerCommand;
use App\Form\AddChallengerType;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route(
 *     name="challenger_add",
 *     path="/challengers/add",
 *     methods={"GET", "POST"}
 * )
 */
class AddChallenger
{
    private FormFactoryInterface $formFactory;

    private Environment $twig;

    private MessageBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        Environment $twig,
        MessageBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->twig        = $twig;
        $this->commandBus  = $commandBus;
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
            $this->commandBus->dispatch($command);
        }

        return new Response($this->twig->render('add_challenger.html.twig', [
            'form' => $form->createView(),
        ]));
    }
}
