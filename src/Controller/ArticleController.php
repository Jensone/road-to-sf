<?php

namespace App\Controller;

use App\Form\ArticleForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article')]
final class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $ar,
        EntityManagerInterface $em
        ){}

    // Route "/articles" menant à la liste des articles
    #[Route('s', name: 'articles', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $query = $this->ar->findBy(['is_published' => true, 'is_archived' => false], ['id' => 'DESC']);
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('article/index.html.twig', [
            'articles' => $pagination
        ]);
    }

    // Route "/article" menant à un article
    #[Route('/{slug}', name: 'article', methods: ['GET'])]
    public function view(string $slug): Response
    {
        return $this->render('article/view.html.twig', [
            'article' => $this->ar->findOneBySlug($slug)
        ]);
    }

    // Route "/article/{slug}/edit" menant à la modification d'un article
    #[Route('/{slug}/edit', name: 'article_edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, Request $request): Response
    {
        $article = $this->ar->findOneBySlug($slug); // Récupération de l'article
        $form = $this->createForm(ArticleForm::class, $article); // Mise en place du formulaire
    
        return $this->render('article/edit.html.twig', [
            'articleForm' => $form // Envoi du formulaire à la vue
        ]);
    }
}
