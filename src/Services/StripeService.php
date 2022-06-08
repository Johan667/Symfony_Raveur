<?php

namespace App\Services;

use Stripe\Stripe;
use App\Entity\Article;
use Stripe\PaymentIntent;

class StripeService{

    private $clefPrive;

    public function __construct()
    {
        // Si le site est en mode dev on utilise le systeme de paiement test sinon le real
        if($_ENV['APP_ENV'] === 'dev'){
            $this->clefPrive = $_ENV['STRIPE_SECRET_KEY_TEST'];
        }else{
            $this->clefPrive = $_ENV['STRIPE_SECRET_KEY_REAL'];
        }
    }

    // Récupère le prix 
    public function paymentIntent(Article $article){

        \Stripe\Stripe::setApiKey($this->clefPrive);

        return \Stripe\PaymentIntent::create([
            'amount'=> $article->getPrix();
        ]);
    }

}