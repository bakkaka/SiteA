<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\City;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class Article1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('date', DateTimeType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
			->add('user', EntityType::class, [
                'label' => 'User',
                'class' => User::class,
                'choice_label' => 'username'
				])
            ->add('subtitle', TextType::class)
            ->add('content', TextareaType::class)
            ->add('slug', TextType::class)
            ->add('articlecategories')
			  
            ->add('image', ImageType::class , ['label' => false])
			->add('keywords', CollectionType::class, [
			       'entry_type' => KeywordType::class,
				   'allow_add' => true,
				   'allow_delete' => true,
			       'by_reference' => false
				   
			    
				  ])
			 ->add('cities', EntityType::class, [
                'label' => 'Ville',
                'class' => City::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
			      
			
			->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
}

}