<?php

namespace App\Form;

use App\Entity\RepLog;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Knp\Bundle\TimeBundle\DateTimeFormatter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RepLogType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reps')
            ->add('item')
            ->add('totalWeightLifted')
            ->add('user', EntityType::class, [
                'class' => User::class,
                //'choices' => $this->userRepository->findAllEmailAlphabetical(),
                'placeholder' => 'Choose an author',
                'invalid_message' => 'Symfony is too smart for your hacking!'
                // 'choice_label' => function(User $user) {
                //    return sprintf('(%d) %s', $user->getId(), $user->getEmail());
                // }

            ])
			 ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RepLog::class,
        ]);
    }
}
