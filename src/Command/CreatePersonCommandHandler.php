<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Person as PersonEntity;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreatePersonCommandHandler implements MessageHandlerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(CreatePersonCommand $createPersonCommand): void
    {
        $em = $this->registry->getManagerForClass(PersonEntity::class);

        $personEntity = new PersonEntity();
        $personEntity->id = $createPersonCommand->id;
        $personEntity->name = $createPersonCommand->name;

        $em->persist($personEntity);
    }
}
