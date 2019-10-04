<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\TetrisGame as TetrisGameEntity;
use App\ReadModel\TetrisGame;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FetchTetrisGameQueryHandler
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
     * @param FetchTetrisGameQuery $fetchTetrisGameQuery
     *
     * @return TetrisGame
     *
     * @throws NotFoundHttpException
     */
    public function __invoke(FetchTetrisGameQuery $fetchTetrisGameQuery): TetrisGame
    {
        $em = $this->registry->getEntityManagerForClass(TetrisGameEntity::class);

        $tetrisGameRepository = $em->getRepository(TetrisGameEntity::class);

        $qb = $tetrisGameRepository->createQueryBuilder('tetris_game');

        $qb
            ->where($qb->expr()->eq('tetris_game.id', ':id'))
            ->setParameter('id', $fetchTetrisGameQuery->tetrisGameId)
        ;

        $tetrisGameEntity = $qb->getQuery()->getOneOrNullResult();

        if (null === $tetrisGameEntity) {
            throw new NotFoundHttpException("Resource `tetris-game` not found for id `{$fetchTetrisGameQuery->tetrisGameId}`");
        }

        $tetrisGame       = new TetrisGame();
        $tetrisGame->id   = $tetrisGameEntity->id;
        $tetrisGame->date = $tetrisGameEntity->date->format('d/m/Y');

        return $tetrisGame;
    }
}
