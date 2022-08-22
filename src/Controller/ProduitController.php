<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProduitController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager, StripeService $stripeService)
    {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

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
        $articles = $ar->findBy(['tendance' => 1]);

        return $this->render('produit/detail.html.twig', [
            'articles' => $articles,
            'article' => $article,
        ]);
    }

    public function creation_commande(array $ressource, Article $article, User $user)
    {
        // une fois que le paiement est effectuer on injecte la commande en bdd
        $commande = new Commande();

        $commande->setDateCommande(new \Datetime());
        $this->em->persist($commande);
        $this->em->flush();
    }
}
