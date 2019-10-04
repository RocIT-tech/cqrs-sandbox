<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\TetrisGame as TetrisGameEntity;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CreateTetrisGameCommandHandler
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(CreateTetrisGameCommand $createTetrisGameCommand): void
    {
        $em = $this->registry->getEntityManagerForClass(TetrisGameEntity::class);

        $tetrisGameEntity = new TetrisGameEntity();
        $tetrisGameEntity->id = $createTetrisGameCommand->id;
        $tetrisGameEntity->date = $createTetrisGameCommand->date;

        $em->persist($tetrisGameEntity);
        $em->flush();
    }
}
