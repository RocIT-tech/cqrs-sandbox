<?php

declare(strict_types=1);

namespace App\Form\Choices;

use App\Entity\Person;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonChoiceType extends AbstractType
{
    private ManagerRegistry $registry;

    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choice_loader' => new CallbackChoiceLoader(function () {
                $em         = $this->registry->getManagerForClass(Person::class);
                $repository = $em->getRepository(Person::class);

                $qb = $repository->createQueryBuilder('person');

                $qb
                    ->orderBy('person.name', 'DESC');

                /** @var Person[] $persons */
                $persons = $qb->getQuery()->getResult();

                foreach ($persons as $person) {
                    yield $person->name => $person->id;
                }
            }),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
