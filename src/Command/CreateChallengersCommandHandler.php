<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\CreateChallengersCommand\Challenger;
use App\Entity\Challenger as ChallengerEntity;
use App\Entity\Person;
use App\Entity\TetrisGame;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function array_reduce;

class CreateChallengersCommandHandler
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(CreateChallengersCommand $createChallengersCommand): void
    {
        $em = $this->registry->getEntityManagerForClass(ChallengerEntity::class);

        $tetrisGame = $em->getReference(TetrisGame::class, $createChallengersCommand->tetrisGameId);

        $challengers = array_reduce(
            $createChallengersCommand->challengers,
            static function (array $challengers, Challenger $challenger) use ($em, $tetrisGame): ChallengerEntity {
                $challengerEntity             = new ChallengerEntity();
                $challengerEntity->person     = $em->getReference(Person::class, $challenger->personId);
                $challengerEntity->tetrisGame = $tetrisGame;
                $challengerEntity->id         = $challenger->id;
                $challengerEntity->rank       = $challenger->rank;

                $em->persist($challengerEntity);

                return $challengerEntity;
            },
            []
        );

        $em->flush();
    }
}
