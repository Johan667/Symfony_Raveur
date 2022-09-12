<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Form\CommandeType;
use App\Repository\ArticleRepository;
use Doctrine\Persistence\ManagerRegistry;
use Stripe\StripeClient;
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
    public function index(ManagerRegistry $doctrine, Session $session, ArticleRepository $articleRepository, Request $request): Response
    {
        $amount = 0;
        $session = $request->getSession();
        $panier = $session->get('panier', []);
        $panierWithData = [];

        // $intent = $request->request->get('stripeToken');

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
        $user = $commande = $doctrine->getRepository(User::class);
        $commande = $doctrine->getRepository(Commande::class);
        $formCommande = $this->createForm(CommandeType::class);
        $formCommande->handleRequest($request);

        $stripe = new StripeClient(
            'sk_test_51L6amqAgDjI611jf49n3RURuEVn6KbawPxt0CKby4wsENM9plWmKeqkq7Cm3Sl1W4JcvjewbvVCBrwyA5knu6b2500QdV5lalL'
          );
        $stripe->tokens->create([
            'person' => [
              'first_name' => 'Jane',
              'last_name' => 'Doe',
              'relationship' => ['owner' => true],
            ],
          ]);

        // dd($stripe);

        $commande = new Commande();

        if ($formCommande->isSubmitted() && $formCommande->isValid()) {
            $commande = $formCommande->getData();
            $adresse_livraison = $request->request->get('adresse_livraison');
            // $cp_livraison = $request->request->get('cp_livraison');
            // $ville = $request->request->get('ville_livraison');
            // $pays_livraison = $request->request->get('pays_livraison');
            $devise = '€';

            $commande
                ->setUser($this->getUser())
                ->setPrixTotal($amount)
                ->setDevise($devise)
                ->setDateCommande(new \DateTimeImmutable())
                ->setAdresseLivraison('adresse_livraison', $adresse_livraison)
                ->setPaysLivraison('FRANCE');
            //  ->setArticleId('items', $panierWithData);

            $doctrine->getManager()->persist($commande);
            $doctrine->getManager()->flush();
        }

        $amount = 0;
        $panier = $session->get('panier', []);
        $panierWithData = [];

        foreach ($panierWithData as $item) {
            $totalItem = $item['article']->getPrix() * $item['quantite'];
            $amount += $totalItem;
        }

        return $this->render('paiement/index.html.twig', [
            'items' => $panierWithData,
            'amount' => $amount,
            'panier' => $panier,
            'commande' => $commande,
            'formCommande' => $formCommande->createView(),
        ]);
    }
}
