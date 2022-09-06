<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, CategorieRepository $ca, ArticleRepository $ar, Request $request): Response
    {
        // Je cherche dans le repository Categorie pour afficher les articles
        $categories = $ca->findAll();
        $articles = $ar->findBy(['nouveau' => 1]);

        $formArticle = $this->createForm(SearchArticleType::class);
        $search = $formArticle->handleRequest($request);

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            // On cherches les articles qui correspondent au mots clefs
            $articlesFound = $ar->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData()
        );

            return $this->render('search/index.html.twig', [
                'articlesFound' => $articlesFound,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'articles' => $articles,
            'formArticle' => $formArticle->createView(),
        ]);
    }
}
