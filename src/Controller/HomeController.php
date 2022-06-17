<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, CategorieRepository $ca, ArticleRepository $ar): Response
    {
        // Je cherche dans le repository Categorie l'ensemble des informations
        $categories = $ca->findAll();
        $articles = $ar->findBy(["nouveau" => 1]);


        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'articles'=>$articles,
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
