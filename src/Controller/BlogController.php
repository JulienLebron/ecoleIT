<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/blog/show/{id}', name: 'blog_show')]
    public function show(Article $article): Response
    {

        return $this->render('blog/show.html.twig', [
            'title' => "Détail de l'article",
            'article' => $article
        ]);
    }

    #[Route('/blog/new', name: 'blog_create')]
    #[Route('/blog/edit/{id}', name: 'blog_edit')]
    public function form(Request $request, EntityManagerInterface $manager, Article $article = null ): Response
    {
        // si nous ne récupérons pas d'objet $article, nous en créons un vide et prêt à être rempli
        if(!$article) 
        {
            $article = new Article;
            $article->setCreatedAt(new \DateTimeImmutable());
        }
        // la classe Request contient les données véhiculées par les superglobales ($_POST, $_GET)
        // createForm() permet de récupérer un modèle de formulaire
        $form = $this->createForm(ArticleType::class, $article); // je lie le formulaire à mon objet $article
        $form->handleRequest($request);
        // handleRequest() permet d'insérer les données du formulaire dans l'objet $article
        // elle permet aussi de faire les vérifications sur le formulaire (quelle méthode? est-ce que les sont remplis ? etc) 

        if($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($article); // prépare à l'insertion de l'article en BDD
            $manager->flush(); // exécute la requête d'insertion
            return $this->redirectToRoute('blog_show', [
                'id' => $article->getId()
            ]);
        }

        return $this->render('blog/form.html.twig', [
            // createView() renvoie un objet représentant l'affichage du formulaire
            'formArticle' => $form->createView()
        ]);
    }



}
