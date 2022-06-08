<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/produit/{id}", name="produit_detail")
     */
    // J'informe dans le routing que je veux afficher le detail d'un produit en passant par son ID
    public function produitDetail(ManagerRegistry $doctrine, ArticleRepository $ar, Article $article): Response
    {  
        // $articles = $ar->find($id);
        return $this->render('produit/detail.html.twig', [    
            'article'=> $article,  
        ]);
    }

}
