<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Article;
use App\Entity\Categorie;
use App\Form\TriType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function AfficherCategorie(Categorie $categorie, ArticleRepository $repository, Request $request): Response
    {
        $data = new SearchData();
        $TriForm = $this->createForm(TriType::class);
        $article = $repository->findSearch($data);
        $TriForm->handleRequest($request);

        return $this->render('produit/index.html.twig', [
            'categorie' => $categorie,
            'article' => $article,
            'TriForm' => $TriForm->createView(),
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
