<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RGPDController extends AbstractController
{
    /**
     * @Route("/confidentialite", name="confidentialite")
     */
    public function PolitiqueCofidentialite(): Response
    {
        return $this->render('cnil/index.html.twig', [
        ]);
    }

    /**
     * @Route("/mentions-legal", name="mentions")
     */
    public function Mention(): Response
    {
        return $this->render('cnil/mentions.html.twig', [
        ]);
    }

    /**
     * @Route("/cookie", name="cookie")
     */
    public function Cookie(): Response
    {
        return $this->render('cnil/cookie.html.twig', [
        ]);
    }

    /**
     * @Route("/cgv", name="cgv")
     */
    public function Cgv(): Response
    {
        return $this->render('cnil/cgv.html.twig', [
        ]);
    }
}
