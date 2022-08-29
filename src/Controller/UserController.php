<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\ResetPasswordType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user")
     */
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $password = $this->createForm(ResetPasswordType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setNom($form->get('nom')->getData())
                 ->setPrenom($form->get('prenom')->getData())
                 ->setEmail($form->get('email')->getData())
                 ->setTelephone($form->get('telephone')->getData())
                 ->setSociete($form->get('societe')->getData());
            $doctrine->getManager()->flush(); // on applique les modification
            $this->addFlash('sucess','Vos informations ont bien été mis a jour !');
            return $this->redirectToRoute("app_user"); 
        }

            $password->handleRequest($request);
            if ($reset->isSubmitted() && $reset->isValid()) {
                $oldPass = $password->get('oldPassword')->getData();
                $newPass = $password->get('newPassword')->getData();
                if (password_verify($oldPass, $user->getPassword())) { // on verifie que l'ancien mot de passe coresponde au nouveau
                    $user->setPassword( $userPasswordHasher->hashPassword($user, $newPass) ); // on hash le mot de passe et on l'associe a l'utilisateur
                    $doctrine->getManager()->flush(); // on l'enregistre
                    $this->addFlash('sucess','Votre mot de passe a bien été mis a jour !');
                    return $this->redirectToRoute("app_user"); 
                

        return $this->render('user/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'password' => $password->createView(), ]);
    

        }