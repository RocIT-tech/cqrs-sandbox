<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Person as PersonEntity;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CreatePersonCommandHandler
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
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
        $em->flush();
    }
}
