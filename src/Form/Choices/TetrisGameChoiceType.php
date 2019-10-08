<?php

declare(strict_types=1);

namespace App\Form\Choices;

use App\Entity\TetrisGame;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TetrisGameChoiceType extends AbstractType
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choice_loader' => new CallbackChoiceLoader(function () {
                $em         = $this->registry->getManagerForClass(TetrisGame::class);
                $repository = $em->getRepository(TetrisGame::class);

                $qb = $repository->createQueryBuilder('tetris_game');

                $qb
                    ->orderBy('tetris_game.date', 'DESC');

                /** @var TetrisGame[] $tetrisGames */
                $tetrisGames = $qb->getQuery()->getResult();

                foreach ($tetrisGames as $tetrisGame) {
                    yield $tetrisGame->date->format('d/m/Y') => $tetrisGame->id;
                }
            }),
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
