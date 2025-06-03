<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CommentController extends AbstractController
{
    #[Route('/comment/new/{article}', name: 'comment_new', methods: ['POST'])]
    public function new(
            int $article, 
            Request $request, // permet de gérer les requêtes HTTP (params, etc..)
            ArticleRepository $ar,
            EntityManagerInterface $em // permet d'interagir avec la BDD
    ): Response {
        $articleComment = $ar->find($article); // Récupération de l'article
        $user = $this->getUser(); // Récupération de l'utilisateur en cours
        if(!$article) { // Ce sera ignorer si l'article existe
            $this->addFlash('error', "L'article n'existe pas");
            return $this->redirectToRoute('articles');
        }

        $comment = new Comment();
        $comment
            ->setAuthor($user)
            ->setArticle($articleComment)
            ->setContent($request->request->get('content'))
        ;

        $em->persist($comment); // Enregistrement de l'article (query SQL)
        $em->flush($comment); // Exécution de l'enregistrement en BDD

        $this->addFlash('success', "Votre commentaire est en cours de traitement");
        return $this->redirectToRoute('article', ['slug' => $articleComment->getSlug()]);
    }
}
