<?php

declare(strict_types=1);

namespace App\Command;

use App\Command\CreateChallengersCommand\Challenger;
use App\Entity\Challenger as ChallengerEntity;
use App\Entity\Person;
use App\Entity\TetrisGame;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use function array_reduce;

class CreateChallengersCommandHandler implements MessageHandlerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(CreateChallengersCommand $createChallengersCommand): void
    {
        $em = $this->registry->getManagerForClass(ChallengerEntity::class);

        $tetrisGame = $em->getReference(TetrisGame::class, $createChallengersCommand->tetrisGameId);

        $challengers = array_reduce(
            $createChallengersCommand->challengers,
            static function (array $challengers, Challenger $challenger) use ($em, $tetrisGame): array {
                $challengerEntity             = new ChallengerEntity();
                $challengerEntity->person     = $em->getReference(Person::class, $challenger->personId);
                $challengerEntity->tetrisGame = $tetrisGame;
                $challengerEntity->id         = $challenger->id;
                $challengerEntity->rank       = $challenger->rank;

                $em->persist($challengerEntity);
                $challengers[] = $challengerEntity;

                return $challengers;
            },
            []
        );
    }
}
