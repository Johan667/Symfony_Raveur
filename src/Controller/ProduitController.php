<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\TriType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="app_produit")
     */
    public function index(): Response
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    /**
     * @Route("/produit/categorie/{id}", name="produit_categorie")
     */
    public function AfficherCategorie(Categorie $categorie, ArticleRepository $repository, Request $request, Session $session): Response
    {
        $triForm = $this->createForm(TriType::class, null);
        // Crée le formulaire configuré dans le dossier FORM

        $triForm->handleRequest($request);
        // traite les données du formulaire

        if ($triForm->isSubmitted() && $triForm->isValid()) {
            $articlesFound = $repository->findSearch(
                    $triForm->get('nouveau')->getData(),
                    $triForm->get('tendance')->getData(),
                );

            return $this->render('search/index.html.twig', [
                    'articlesFound' => $articlesFound,
                ]);
            // on passe le formulaire a la fonction du repository qui est un tableau classic : ArticleRepository
        }

        return $this->render('produit/index.html.twig', [
            'categorie' => $categorie,
            'TriForm' => $triForm->createView(),
        ]);
    }

    /**
     * @Route("/produit/{id}", name="produit_detail")
     */
    // J'informe dans le routing que je veux afficher le detail d'un produit en passant par son ID
    public function produitDetail(ManagerRegistry $doctrine, ArticleRepository $ar, Article $article): Response
    {
        $articles = $ar->findBy(['tendance' => 1]);

        return $this->render('produit/detail.html.twig', [
            'articles' => $articles,
            'article' => $article,
        ]);
    }
}
