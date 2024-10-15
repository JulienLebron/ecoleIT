<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // l'objet $builder permet de créer un formulaire
        // la méthode add() permet d'ajouter un champ au formulaire
        $builder
            ->add('title')
            ->add('content')
            ->add('image')
            // nous allons commenter ce champ car nous ne voulons pas que l'utilisateur entre lui m$eme une date de création. Cela sera fait lors du traitement du formulaire, avant l'insertion en bdd de l'article
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
