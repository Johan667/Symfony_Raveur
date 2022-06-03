<?php

namespace App\Service\Panier;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService{

    protected $session;

    public function __construct(SessionInterface $session){
        $this->session = $session;
    }

    public function ajouterArticle(int $id){

        $session = $request->getSession();
    
        $panier = $this->session->get('panier', []);
   
        if(!empty($panier[$id])){
           
         $panier[$id] += $request->request->get('quantite');

        }else{
            $panier[$id] = $request->request->get('quantite');
        }

        $this->session->set('panier', $panier);
    }

    public function supprimerArticle(int $id){
        
    }

    // public function PanierEntier(): array 
    // {
        
    // }

    // public function getTotal(){
        
    // }
}