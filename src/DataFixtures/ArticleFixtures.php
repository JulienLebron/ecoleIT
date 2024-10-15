<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 20; $i++) 
        {
            $article = new Article(); 
            // on instancie la class Article qui se trouve dans le dossier App\Entity
            // Nous pouvons maintenant faire appel au setteur pour créer des articles
            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.")
                    ->setImage("https://picsum.photos/200/300")
                    ->setCreatedAt(new \DateTimeImmutable());
                    // permet de sauvegarder l'article en attendant le flush
                    $manager->persist($article);
        }
        // le flush permet d'exécuter la requête SQL
        $manager->flush();
    }
}
