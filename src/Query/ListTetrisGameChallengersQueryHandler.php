<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\Challenger as ChallengerEntity;
use App\ReadModel\Challenger;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function array_map;

class ListTetrisGameChallengersQueryHandler
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
     * @param ListTetrisGameChallengersQuery $listTetrisGameChallengersQuery
     *
     * @return Challenger[]
     */
    public function __invoke(ListTetrisGameChallengersQuery $listTetrisGameChallengersQuery): array
    {
        $em         = $this->registry->getEntityManagerForClass(ChallengerEntity::class);
        $repository = $em->getRepository(ChallengerEntity::class);

        $qb = $repository->createQueryBuilder('challenger');

        $qb
            ->join('challenger.person', 'person')
            ->where($qb->expr()->eq('challenger.tetrisGame', ':tetrisGame'))
            ->setParameter('tetrisGame', $listTetrisGameChallengersQuery->tetrisGameId)
            ->orderBy('challenger.rank', 'ASC')
        ;

        $challengerEntities = $qb->getQuery()->getResult();

        return array_map(static function (ChallengerEntity $challengerEntity): Challenger {
            $challenger           = new Challenger();
            $challenger->personId = $challengerEntity->person->id;
            $challenger->rank     = $challengerEntity->rank;
            $challenger->name     = $challengerEntity->person->name;

            return $challenger;
        }, $challengerEntities);
    }
}
