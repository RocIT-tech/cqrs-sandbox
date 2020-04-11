<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\TetrisGame as TetrisGameEntity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateTetrisGameCommandHandler implements MessageHandlerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(CreateTetrisGameCommand $createTetrisGameCommand): void
    {
        $em = $this->registry->getManagerForClass(TetrisGameEntity::class);

        $tetrisGameEntity = new TetrisGameEntity();
        $tetrisGameEntity->id = $createTetrisGameCommand->id;
        $tetrisGameEntity->date = $createTetrisGameCommand->date;

        $em->persist($tetrisGameEntity);
    }
}
