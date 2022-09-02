<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository, Request $request): Response
    {
        $amount = 0;
        $panier = $session->get('panier', []);
        $panierWithData = [];

        $intent = $request->request->get('stripeToken');

        // Tableau associatif qui contient un couple avec toutes les informations du produit et la quantité
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

        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'amount' => $amount,
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="panier_ajouter")
     */
    // Pour récupérer le produit en session on utilise HttpFoundation, on veux recuperer l'objet en session grace a request //
    // https://symfony.com/doc/current/introduction/http_fundamentals.html#requests-and-responses-in-symfony << utiliser la doc pour expliquer

    // container de service https://www.youtube.com/watch?v=frAXgi9D6fo php bin/console debug:autowiring session demander le service de session interface

    public function ajouterArticle(int $id, SessionInterface $session, Request $request, ArticleRepository $article): Response
    {
        $session = $request->getSession();

        $panier = $session->get('panier', []);

        if (!empty($panier[$id])) {
            $panier[$id] += $request->request->get('quantite');
        } else {
            $panier[$id] = $request->request->get('quantite');
        }

        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */
    public function SupprimerArticle($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/success", name="success")
     */
    public function success()
    {
        return $this->render('paiement/success.html.twig');
    }

    /**
     * @Route("/error", name="error")
     */
    public function error()
    {
        return $this->render('paiement/error.html.twig');
    }
}
