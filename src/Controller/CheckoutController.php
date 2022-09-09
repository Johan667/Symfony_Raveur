<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function index(Session $session, ArticleRepository $articleRepository, Request $request): Response
    {
        $amount = 0;
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $panierWithData = [];

        $intent = $request->request->get('stripeToken');

        foreach ($panier as $id => $quantite) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantite' => $quantite,
            ];
        }
        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantite'];
            $amount += $totalItem;
        }

        $formCommande = $this->createForm(CommandeType::class);
        $formCommande->handleRequest($request);

        // $commande = new Commande();
        // if ($formCommande->isSubmitted() && $formCommande->isValid()) {
        //     $commande
        //         ->setDateCommande(new \DateTimeImmutable())
        //         ->setAdresseLivraison('adresse_livraison')
        //         ->setCpLivraison('cp_livraison')
        //         ->setVilleLivraison('ville_livraison')
        //         ->setPaysLivraison('pays_livraison');

        //     $doctrine->getManager()->persist($commande);
        //     $doctrine->getManager()->flush();
        // }

        $amount = 0;
        $panier = $session->get('panier', []);
        $panierWithData = [];

        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantite'];
            $amount += $totalItem;
        }

        return $this->render('checkout/index.html.twig', [
            'items' => $panierWithData,
            'amount' => $amount,
            'panier' => $panier,
            'formCommande' => $formCommande->createView(),
        ]);
    }
}
