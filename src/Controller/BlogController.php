<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'app_blog')]
    // une route est définie par 2 arguments: son chemin (/blog) et son nom (app_blog)
    public function index(): Response
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'age' => 16,
        ]);
        // render() permet d'afficher le contenu d'un template
        // elle va chercher directement dans le dossier template
    }

    #[Route('/', name: 'app_home')]
    public function home(ArticleRepository $repo): Response
    {
        // pour récupérer le repository, je le passe en paramètre de la méthode
        // cela s'appel une injection de dépendance
        // j'utilise la méthode findAll() pour récupérer tout les articles en BDD
        $articles = $repo->findAll();
        dump($articles);

        return $this->render('blog/home.html.twig', [
            // j'envoi tout les articles sur la vue
            'articles' => $articles
        ]);
    }

    #[Route('/blog/{id}', name: 'blog_show')]
    public function show(Article $article): Response
    {

        return $this->render('blog/show.html.twig', [
            'title' => "Détail de l'article",
            'article' => $article
        ]);
    }

}
