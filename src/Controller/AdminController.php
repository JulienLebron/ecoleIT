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

class AdminController extends AbstractController
{
    #[Route('/admin/articles', name: 'admin_articles')]
    public function adminArticles(ArticleRepository $repo, EntityManagerInterface $em): Response
    {   
        $columns = $em->getClassMetadata(Article::class)->getFieldNames();
        $articles = $repo->findAll();

        return $this->render('admin/index.html.twig', [
            'articles' => $articles,
            'columns' => $columns
        ]);
    }

    #[Route('/admin/article/new', name: 'admin_new_article')]
    #[Route('/admin/{id}/edit', name: 'admin_edit_article')]
    public function form(Request $request, EntityManagerInterface $manager, Article $article = null ): Response
    {
        // si nous ne récupérons pas d'objet $article, nous en créons un vide et prêt à être rempli
        if(!$article) 
        {
            $article = new Article;
            $article->setCreatedAt(new \DateTimeImmutable());
        }
        $editMode = $article->getId() !== NULL;
        // la classe Request contient les données véhiculées par les superglobales ($_POST, $_GET)
        // createForm() permet de récupérer un modèle de formulaire
        $form = $this->createForm(ArticleType::class, $article); // je lie le formulaire à mon objet $article
        $form->handleRequest($request);
        // handleRequest() permet d'insérer les données du formulaire dans l'objet $article
        // elle permet aussi de faire les vérifications sur le formulaire (quelle méthode? est-ce que les sont remplis ? etc) 

        if($form->isSubmitted() && $form->isValid())
        {
            if($editMode) 
            {
                $article->setUpdatedAt(new \DateTime());
            }
            $manager->persist($article); // prépare à l'insertion de l'article en BDD
            $manager->flush(); // exécute la requête d'insertion
            $editMode ? $this->addFlash('success', "✅ L'article à été mis à jour avec succès!") : $this->addFlash('success', "✅ L'article à été crée avec succès!");
            return $this->redirectToRoute('admin_articles');
        }
        
        return $this->render('blog/form.html.twig', [
            // createView() renvoie un objet représentant l'affichage du formulaire
            'formArticle' => $form->createView()
        ]);
    }
    
    #[Route('/admin/{id}/delete/article', name: 'admin_delete_article')]
    public function deleteArticle(Article $article, EntityManagerInterface $em)
    {
        $em->remove($article);
        $em->flush();

        $this->addFlash('success', '⚙️ Article supprimé avec succès!');
        return $this->redirectToRoute('admin_articles');
    }
}
