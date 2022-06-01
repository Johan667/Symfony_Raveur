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
    public function index(SessionInterface $session, ArticleRepository $article): Response
    {
        $panier = $session->get('panier', []);

        $panierWithData = [];
        // Tableau associatif qui contient un couple avec toutes les informations du produit et la quantité
        foreach($panier as $id => $quantite){
            $panierWithData[] = [
                'article'=> $article->find($id),
                'quantite'=> $quantite,

            ];
        }

        return $this->render('panier/index.html.twig', [
            'total_articles' => $panierWithData,
        ]);
    }
    /**
     * @Route("/panier/ajouter/{id}", name="panier_ajouter")
     */
    // Pour récupérer le produit en session on utilise HttpFoundation, on veux recuperer l'objet en session grace a request //
    // https://symfony.com/doc/current/introduction/http_fundamentals.html#requests-and-responses-in-symfony << utiliser la doc pour expliquer

    // container de service https://www.youtube.com/watch?v=frAXgi9D6fo php bin/console debug:autowiring session demander le service de session interface

    public function ajouterArticle($id, SessionInterface $session, Request $request): Response
    {
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
    public function SupprimerArticle(Article $article): Response
    {
        return $this->render('panier/index.html.twig', [
            'article' => '$article',
        ]);
    }
}
