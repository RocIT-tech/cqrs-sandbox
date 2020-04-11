<?php

declare(strict_types=1);

namespace App\Query;

use App\Entity\Person as PersonEntity;
use App\ReadModel\ListPerson;
use App\ReadModel\Person;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use function array_map;

class ListPersonsQueryHandler implements MessageHandlerInterface
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function __invoke(ListPersonsQuery $listPersonsQuery): ListPerson
    {
        $em         = $this->registry->getManagerForClass(PersonEntity::class);
        $repository = $em->getRepository(PersonEntity::class);

        $countQb = $repository->createQueryBuilder('person');

        $countQb
            ->select($countQb->expr()->count('person.id'));

        $count = $countQb->getQuery()->getSingleScalarResult();

        $listPerson        = new ListPerson();
        $listPerson->page  = $listPersonsQuery->page;
        $listPerson->count = $count;

        $qb = $repository->createQueryBuilder('person');

        $qb
            ->select('person')
            ->setFirstResult(($listPersonsQuery->page - 1) * $listPersonsQuery->max)
            ->setMaxResults($listPersonsQuery->max)
        ;

        $personEntities = $qb->getQuery()->getResult();

        $listPerson->persons = array_map(static function (PersonEntity $personEntity): Person {
            $person       = new Person();
            $person->id   = $personEntity->id;
            $person->name = $personEntity->name;

            $person->_links['tetris_games'] = "/api/persons/{$person->id}/tetris-games";

            return $person;
        }, $personEntities);

        return $listPerson;
    }
}
