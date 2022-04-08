<?php

namespace App\Form;

use App\Entity\quizz;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizzType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('section')
            ->add('title')
            ->add('question', TextType::class, [
                'label' => 'Question n°1'
            ])
            ->add('answer', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrai' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('responseInstructeur', null, [
                'mapped' => false,
                'label' => 'Reponse instructeur :'

            ])
            ->add('question2', TextType::class, [
                'label' => 'Question n°2'
            ])
            ->add('answer2', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrai' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('responseInstructeur2', null, [
                'mapped' => false,
                'label' => 'Response instructeur :'
            ])
            ->add('question3', TextType::class, [
                'label' => 'Question n°3'
            ])
            ->add('answer3', ChoiceType::class, [
                'label' => 'Réponse : ',
                'choices' => [
                    'vrai' => 1,
                    'faux' =>0,
                ],
                'expanded' => true,
                'multiple' => false
            ])
            ->add('responseInstructeur3', null, [
                'mapped' => false,
                'label' => 'Réponse instructeur :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => quizz::class,
        ]);
    }
}
