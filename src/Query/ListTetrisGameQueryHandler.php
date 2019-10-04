<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\TetrisGame as TetrisGameEntity;
use App\ReadModel\TetrisGame;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function array_map;

class ListTetrisGameQueryHandler
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param ListTetrisGameQuery $listTetrisGameQuery
     *
     * @return TetrisGame[]
     */
    public function __invoke(ListTetrisGameQuery $listTetrisGameQuery): array
    {
        $em = $this->registry->getEntityManagerForClass(TetrisGameEntity::class);

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
