<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CheckoutType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/checkout", name="app_checkout")
     */
    public function index(ManagerRegistry $doctrine, Session $session, Commande $commande): Response
    {
        $commande = $doctrine->getRepository(Commande::class)->findAll($commande->getId());
        $formCheckout = $this->createForm(CheckoutType::class, $commande);

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
            'formCheckout' => $formCheckout->createView(),
        ]);
    }
}
