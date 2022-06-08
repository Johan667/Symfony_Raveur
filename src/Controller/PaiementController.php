<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement")
     */
    // Grace au service SessionInterface je rècupere une session ensuite je met en place un panier qui sera un tableau vide
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];

        // Je boucle le panier sur chaque id et leurs quantité pour obtenir :
        // Tableau associatif qui contient un couple avec toutes les informations du produit et la quantité
        foreach ($panier as $id => $quantite) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantite' => $quantite,
            ];
        }
        // Pour obtenir le total par article
        // Je boucle le tableau couple de quantité et produits et calcul le total par artcicle
        $total = 0;
        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }

        return $this->render('paiement/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total,
        ]);
    }
}
