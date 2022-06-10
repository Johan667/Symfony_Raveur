<?php

namespace App\Controller;

use App\Entity\Article;
use App\Controller\ProduitController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/payment/{id}/recap", name="payment", methods={"GET","POST"})
     * @param Article $article
     * @return Response
     */
    public function payment(Article $article, ProduitController $produitController): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('login');
        }
        return $this->render('paiement/index.html.twig', [
            'user'=>$this->getUser(),
            'intentSecret'=> $produitController->intentSecret(),
            'article'=>$article,   
        ]);
}
    /**
     * @Route("/payment/commande/{id}/recap", name="payment_commande", methods={"GET","POST"})
     * @param Article $article
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse\Response
     * @throws \Exception
     */
    public function commande(Article $article, ProduitController $produitController, Request $request){

        $user = $this->getUser();

        if($request->getMethod() === "POST"){
            $ressource = $produitController->stripe($_POST, $article);

            if(null !== $ressource){
            $produitController->creation_commande($ressource, $article, $user);

            return $this->render('paiement/reponse.html.twig', [
                'article'=>$article
            ]);
            }
        }
        return $this->redirectToRoute('payment',['id'=>$article->getId()]);
    }

}
