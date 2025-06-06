<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr' => ['placeholder' => "Titre de l'article"],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('image', DropzoneType::class, [
                'attr' => [
                    'placeholder' => 'Sélectionnez une image',
                    'class' => 'mb-4'
                ],
                'mapped' => false,
                'required' => false
            ])
            ->add('keywords', TextType::class,[
                'attr' => ['placeholder' => "Choisissez des mots-clés"],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['placeholder' => "Résumé de l'article"],
            ])
            ->add('content', TextareaType::class, [
                'attr' => ['placeholder' => "Rédigez votre article ici"],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregister'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
