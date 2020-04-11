<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\TetrisGame as TetrisGameEntity;
use App\ReadModel\TetrisGame;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use function array_map;

class ListTetrisGameQueryHandler implements MessageHandlerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return TetrisGame[]
     */
    public function __invoke(ListTetrisGameQuery $listTetrisGameQuery): array
    {
        $em = $this->registry->getManagerForClass(TetrisGameEntity::class);

        $tetrisGameRepository = $em->getRepository(TetrisGameEntity::class);

        $qb = $tetrisGameRepository->createQueryBuilder('tetris_game');

        $qb
            ->orderBy('tetris_game.date', 'DESC');

        $tetrisGameEntities = $qb->getQuery()->getResult();

        return array_map(
            static function (TetrisGameEntity $tetrisGameEntity): TetrisGame {
                $tetrisGame       = new TetrisGame();
                $tetrisGame->id   = $tetrisGameEntity->id;
                $tetrisGame->date = $tetrisGameEntity->date->format('d/m/Y');

                return $tetrisGame;
            },
            $tetrisGameEntities
        );
    }
}
