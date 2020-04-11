<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Challenger;
use App\Entity\Person;
use App\Entity\TetrisGame;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateChallengerCommandHandler implements MessageHandlerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(CreateChallengerCommand $createChallengerCommand): void
    {
        $em = $this->registry->getManagerForClass(Challenger::class);

        $challenger             = new Challenger();
        $challenger->person     = $em->getReference(Person::class, $createChallengerCommand->personId);
        $challenger->tetrisGame = $em->getReference(TetrisGame::class, $createChallengerCommand->tetrisGameId);
        $challenger->id         = $createChallengerCommand->id;
        $challenger->rank       = $createChallengerCommand->rank;

        $em->persist($challenger);
    }
}
