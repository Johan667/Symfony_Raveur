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
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];

        // Tableau associatif qui contient un couple avec toutes les informations du produit et la quantité
        foreach ($panier as $id => $quantite) {
            $panierWithData[] = [
                'article' => $articleRepository->find($id),
                'quantite' => $quantite,
            ];
        }

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
