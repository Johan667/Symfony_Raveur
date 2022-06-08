<?php

namespace App\Services;

use Stripe\Stripe;
use App\Entity\Article;
use App\Entity\Commande;
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
    
    /**
     * @param Article $article
     * @return \Stripe\paymentIntent
     * @throws \Stripe\Exception\ApiErrorException
     */
    // Associe le prix du produit a une clef
    public function paymentIntent(Article $article){

        \Stripe\Stripe::setApiKey($this->clefPrive);

        return \Stripe\PaymentIntent::create([
            'amount'=> $article->getPrix() * 100,
            'currency'=> Commande::DEVISE,
            'payment_method_types'=>['card'],
        ]);
    }

    // Cette function va déclencher le paiement

    public function paiement($amount, $currency, $description, array $stripeParameter){
        \Stripe\Stripe::setApiKey($this->clefPrive);

        $payment_intent = null;

        if(isset($stripeParameter['stripeIntentId'])){
            $payment_intent = \Stripe\PaymentIntent::retrieve($stripeParameter['stripeIntentId']);
        }

        if($stripeParameter['stripeIntentId'] === 'succeeded'){
            // A FAIRE PAR EXEMPLE PAIEMENT VALIDER
        }else{
            $payment_intent->cancel();
        }
        return $payment_intent;

    }
    /**
     * @param array $stripeParameter
     * @param Article $article
     * @return \Stripe\PaymentIntent|null
     */
    public function stripe(array $stripeParameter, Article $article){
        
        return $this->paiement(
            $article->getPrix() * 100,
            Commande::DEVISE,
            $article->getDenomination(),
            $stripeParameter);
    }
}