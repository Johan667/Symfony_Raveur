<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CheckoutType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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

        // $formCheckout = $this->createForm(CheckoutType::class, $commande);
        // $commande = new Commande();
        // $commande->setAdresseLivraison($formCheckout->get('adresse_livraison'))
        //         ->setdateCommande(new DateTimeImmutable())
        //         ->setCpLivraison($formCheckout->get('cp_livraison'))
        //         ->setVilleLivraison($formCheckout->get('ville_livraison'))
        //         ->setPaysLivraison($formCheckout->get('pays_livraison'));

        // $doctrine->getManager()->persist($commande);
        // $doctrine->getManager()->flush();

        // $formCheckout->handleRequest($request);

        $amount = 0;
        $panier = $session->get('panier', []);
        $panierWithData = [];

        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantite'];
            $amount += $totalItem;
        }
        // $checkout_session = Session::create([
        //     'line_items' => [[
        //       // Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
        //       'price' => $amount,
        //       'quantity' => 1,
        //     ]],
        //     'mode' => 'payment',
        //     'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
        //     'cancel_url' => $this->generateUrl('error', [], UrlGeneratorInterface::ABSOLUTE_URL),
        //     'automatic_tax' => [
        //       'enabled' => true,
        //     ],
        //   ]);

        return $this->render('checkout/index.html.twig', [
            'items' => $panierWithData,
            'amount' => $amount,
            'panier' => $panier,
        ]);
    }
}
