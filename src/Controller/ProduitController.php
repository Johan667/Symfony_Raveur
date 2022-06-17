<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Article;
use App\Services\StripeService;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    public function __construct( EntityManagerInterface $entityManager, StripeService $stripeService){
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
        $articles = $ar->findBy(["tendance" => 1]);
        
        return $this->render('produit/detail.html.twig', [    
            'articles'=> $articles,  
            'article'=>$article,
        ]);
    }

    public function intentSecret(Article $article){
        $intent = $this->stripeService->paymentIntent($article);

        return $intent['client_secret'] ?? null;
    }
    
    public function stripe(array $stripeParameter, Article $article){
        $ressource = null;
        $data = $this->stripeService->stripe($stripeParameter, $article);
        if($data){
            $ressource = [
                'stripeBrand'=>$data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4'=>$data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId'=>$data['charges']['data'][0]['id'],
                'stripeStatus'=>$data['charges']['data'][0]['id']['status'],
                'stripeToken'=>$data['client_secret'],
            ];
        }
        return $ressource;
    }
    /**
     * @param array $ressource
     * @param Article $article
     * @param User $user
     */
    public function creation_commande(array $ressource, Article $article, User $user){
        // une fois que le paiement est effectuer on injecte la commande en bdd
        $commande = new Commande();
        $commande->setUser($user);
        $commande->setArticle($article);
        $commande->setPrix($article->getPrix());
        $commande->setBrandStripe($ressource['stripeBrand']);
        $commande->setLast4Stripe($ressource['stripeLast4']);
        $commande->setIdChargeStripe($ressource['stripeId']);
        $commande->setStripeToken($ressource['stripeToken']);
        $commande->setStatusStripe($ressource['stripeStatus']);
        $commande->setDateCommande(new \Datetime());
        $this->em->persist($commande);
        $this->em->flush();

    }
}
