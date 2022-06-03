<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(SessionInterface $session, ArticleRepository $articleRepository): Response
    {
        $panier = $session->get('panier', []);
        $panierWithData = [];

        // Tableau associatif qui contient un couple avec toutes les informations du produit et la quantité
        foreach($panier as $id =>$quantite){
            $panierWithData[] = [
                'article'=> $articleRepository->find($id),
                'quantite'=> $quantite,

            ];
        }

        $total = 0;

        foreach($panierWithData as $item){
            $totalItem= $item['article']->getPrix() * $item['quantite'];
            $total += $totalItem;
        }
        dd($panierWithData);
        return $this->render('panier/index.html.twig', [
            'items' => $panierWithData,
            'total'=> $total,

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
   
        if(!empty($panier[$id])){
           
         $panier[$id] += $request->request->get('quantite');

        }else{
            $panier[$id] = $request->request->get('quantite');
        }

        $session->set('panier', $panier);

        return $this->render('panier/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */
    public function SupprimerArticle($id, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        if(!empty($panier[$id])){
            unset($panier[$id]);
        }
        $session->set('panier', $panier);

        return $this->redirectToRoute('panier');
      
    }
}
