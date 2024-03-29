<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class RGPDController extends AbstractController
{
    /**
     * @Route("/contact", name="app_contact")
     */
    public function contact(MailerInterface $mailer, Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $mail = (new Email())
            ->from($form->get('email')->getData())
            ->to(new Address('johan.kasri@icloud.com', 'Admin Raveur'))
            ->subject($form->get('objet')->getData())
            ->text($form->get('message')->getData())
            ;

            $mailer->send($mail);
            // $this->addFlash('sucess', 'Votre demande à bien été prise en compte nous y repondrons au plus tard 48 heures après votre demande!');

            return $this->redirectToRoute('app_home');
        }

        return $this->render('RGPD/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

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
