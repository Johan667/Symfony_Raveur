<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(SessionInterface $session)
    {
        $amount = 0;
        $panier = $session->get('panier', []);
        $panierWithData = [];
        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantite'];
            $amount += $totalItem;
        }

        \Stripe\Stripe::setApiKey('sk_test_51L6amqAgDjI611jf49n3RURuEVn6KbawPxt0CKby4wsENM9plWmKeqkq7Cm3Sl1W4JcvjewbvVCBrwyA5knu6b2500QdV5lalL');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $amount * 100,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'automatic_tax' => [
            'enabled' => true,
            ],
        ]);

        return new JsonResponse(['id' => $session->id]);

        return $this->render('paiement/order.html.twig', [
            'panier' => $panier,
        ]);
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
