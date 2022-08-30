<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\SearchArticleType;
use App\Form\TriType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(ManagerRegistry $doctrine, CategorieRepository $ca, ArticleRepository $ar, Request $request): Response
    {
        // Je cherche dans le repository Categorie pour afficher les articles
        $categories = $ca->findAll();
        $articles = $ar->findBy(['nouveau' => 1]);

        $formArticle = $this->createForm(SearchArticleType::class);
        $search = $formArticle->handleRequest($request);

        if ($formArticle->isSubmitted() && $formArticle->isValid()) {
            // On cherches les articles qui correspondent au mots clefs
            $articlesFound = $ar->search(
                $search->get('mots')->getData(),
                $search->get('categorie')->getData()
        );

            return $this->render('search/index.html.twig', [
                'articlesFound' => $articlesFound,
            ]);
        }

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'articles' => $articles,
            'formArticle' => $formArticle->createView(),
        ]);
    }

    /**
     * @Route("/produit/categorie/{id}", name="produit_categorie")
     */
    public function AfficherCategorie(ManagerRegistry $doctrine, Categorie $categorie, Session $session, Request $request): Response
    {
        $TriForm = $this->createForm(TriType::class);

        // STOCKER le resultat dans la session ou créer une session
        if (!$session->get('filter')) {
            $filterSession = [
                        'categorie' => null,
                        'nouveau' => null,
                        'tendance' => null,
                    ];
            $session->set('filter', $filterSession);
        } else {
            $filterSession = $session->get('filter');
        }
        $TriForm->handleRequest($request);
        if ($TriForm->isSubmitted() && $TriForm->isValid()) { // si l'utilisateur decide de trier les produits
            foreach ($filterSession as $key => $value) { // on gere le tableau de filtre en lui inserant les données demandé
                if ($TriForm->get($key)->getData() != null || $TriForm->get($key)->getData() === false) {
                    $filterSession[$key] = $TriForm->get($key)->getData(); // si il ya une donne on la rentre
                } else {
                    $filterSession[$key] = $session->set($key, null); // sinon on met la donné a null
                }
            }
        }

        return $this->render('produit/index.html.twig', [
            'categorie' => $categorie,
            'TriForm' => $TriForm->createView(),
        ]);
    }
}
