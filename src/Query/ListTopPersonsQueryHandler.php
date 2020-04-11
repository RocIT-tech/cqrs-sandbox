<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\Person;
use App\ReadModel\TopPerson;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function array_keys;
use function array_map;
use function round;

class ListTopPersonsQueryHandler
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(ListTopPersonsQuery $query): array
    {
        $em = $this->registry->getManagerForClass(Person::class);

        /** @var EntityRepository $personRepository */
        $personRepository = $em->getRepository(Person::class);

        $qb = $personRepository->createQueryBuilder('p');
        $qb
            ->select(
                'p.id AS personId',
                'p.name AS personName',
                'COUNT(ptg) AS gameCount',
                'SUM(CASE WHEN ptg.rank = 1 THEN 1 ELSE 0 END) AS victoryCount',
                'AVG(ptg.rank) AS averageRank'
            )
            ->leftJoin('p.playedTetrisGames', 'ptg')
            ->groupBy('p.id')
            ->addOrderBy('averageRank', 'ASC')
            ->addOrderBy('victoryCount', 'DESC')
            ->setMaxResults($query->limit)
        ;

        $topPersonArrays = $qb->getQuery()->getResult();

        return array_map(
            static function (array $topPersonArray, $index): TopPerson {
                $topPerson               = new TopPerson();
                $topPerson->personId     = $topPersonArray['personId'];
                $topPerson->personName   = $topPersonArray['personName'];
                $topPerson->gameCount    = $topPersonArray['gameCount'];
                $topPerson->victoryCount = $topPersonArray['victoryCount'];
                $topPerson->isWinner     = 0 === $index;

                $averageRank            = $topPersonArray['averageRank'];
                $topPerson->averageRank = (null !== $averageRank) ? (int) round($averageRank) : null;

                return $topPerson;
            },
            $topPersonArrays,
            array_keys($topPersonArrays),
        );
    }
}
