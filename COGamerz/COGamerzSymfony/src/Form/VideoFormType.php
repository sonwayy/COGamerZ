<?php

namespace App\Form;

use App\Entity\Video;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VideoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => array(
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Title'
                )
            ])
            ->add('body', CKEditorType::class, [
                'attr' => array(
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Content Body'
                )
            ])
            -> add('category', ChoiceType::class, [
                'attr' => array(
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Category'
                ),
                'choices' => [
                    'Play of the week' => 'Play of the week',
                    'World records' => 'World records',
                    'Funny stuff' => 'Funny stuff'
                ]
            ])
            -> add('video', FileType::class, array(
                'required' => false,
                'mapped' => false,
                'attr' => array(
                    'class' => 'form-control form-control-lg',
                    'placeholder' => 'Video'
                )
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Video::class,
        ]);
    }
}
