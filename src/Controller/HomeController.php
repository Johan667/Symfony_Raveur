<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\SearchArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, CategorieRepository $ca, ArticleRepository $ar, Request $request): Response
    {

        $formArticle = $this->createForm(SearchArticleType::class);
        $search = $formArticle->handleRequest($request);

        // Je cherche dans le repository Categorie l'ensemble des informations
        $categories = $ca->findAll();
        $articles = $ar->findBy(["nouveau" => 1]);


        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'articles'=> $articles,     
            'formArticle'=> $formArticle->createView()    
        ]);
    }

    /**
     * @Route("/produit/categorie/{id}", name="produit_categorie")
     */
    public function AfficherCategorie(ManagerRegistry $doctrine, Categorie $categorie): Response
    {
        return $this->render('produit/index.html.twig', [
            'categorie' => $categorie,
        ]);
    }

}
