<?php

declare(strict_types=1);

namespace App\Form;

use App\Command\CreateChallengerCommand;
use App\Form\Choices\PersonChoiceType;
use App\Form\Choices\TetrisGameChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddChallengerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tetrisGameId', TetrisGameChoiceType::class, [
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('personId', PersonChoiceType::class, [
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('rank', IntegerType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => CreateChallengerCommand::class,
            ]);
    }
}
