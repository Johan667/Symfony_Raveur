<?php

namespace App\Controller;

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
     * @Route("/produit/categorie/{idC}", name="produit_categorie_detail")
     */
    public function produitCategorie(ManagerRegistry $doctrine, ArticleRepository $ar, CategorieRepository $ca): Response
    {
        $categorie = $ca->findAll();
        $articles = $ar->findBy(["categorie" => $categorie->getId()]);
        return $this->render('produit/produit_homme.html.twig', [
            'articles'=> $articles,         
        ]);
    }
  
    /**
     * @Route("/produit/homme/{id}", name="produit_homme_detail")
     */
    public function produitHommeDetail(ManagerRegistry $doctrine, ArticleRepository $ar, int $id): Response
    {  
        $articles = $ar->find($id);
        return $this->render('produit/detail.html.twig', [    
            'articles'=> $articles,  
        ]);
    }
    /**
     * @Route("/produit/femme/{id}", name="produit_femme_detail")
     */
    public function produitFemmeDetail(ManagerRegistry $doctrine, ArticleRepository $ar, int $id): Response
    {  
        $articles = $ar->find($id);
        return $this->render('produit/detail.html.twig', [    
            'articles'=> $articles,  
        ]);
    }
}
