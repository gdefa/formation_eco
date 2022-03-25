<?php

namespace App\Form;

use App\Entity\formation;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('picture', FileType::class, [
                'label' => 'Image de la formation',
                'mapped' => false,
                'constraints' => [
        new File([
            'maxSize' => '4096k',
            'mimeTypes' => [
                'image/png',
                'image/jpg',
                'image/jpeg'
            ],
            'mimeTypesMessage' => 'Il faut mettre une image valide (png, jpg, jpeg).',
        ])
        ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => formation::class,
        ]);
    }
}
