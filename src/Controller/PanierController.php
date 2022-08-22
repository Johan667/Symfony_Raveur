<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $amount = 0;
        $panier = $session->get('panier', []);
        $panierWithData = [];

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

        \Stripe\Stripe::setApiKey('sk_test_51L6amqAgDjI611jf49n3RURuEVn6KbawPxt0CKby4wsENM9plWmKeqkq7Cm3Sl1W4JcvjewbvVCBrwyA5knu6b2500QdV5lalL');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Commande Raveur',
                // Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                'amount' => $amount * 100,
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'automatic_tax' => [
            'enabled' => false,
            ],
        ]);

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
