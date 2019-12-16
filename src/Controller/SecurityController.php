<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\GaleryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_registration")
     */
    public function registration(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user, [
            'validation_groups' => array('User', 'registration'),
         ]);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            
            $user->setPassword($hash);
            
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Votre compte à été créé avec succès !');

            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig',  [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/login", name="security_login")
     */
    public function login(){ 
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){}
    
    /**
     * @Route("/mon-compte/", name="security_user_profile")
     * @param UserRepository $user
     */
    public function userProfile(Request $request, ObjectManager $manager, UserRepository $userRepository, GaleryRepository $galeryRepository, UserPasswordEncoderInterface $encoder)
    {
        // If user is not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();
        $galery = $galeryRepository->findAll();

        $form = $this->createForm(RegistrationType::class, $user, [
            'validation_groups' => array('User'),
        ]);
        
        $form->handleRequest($request);
       // dd($form);   
        if($form->isSubmitted() && $form->isValid()) {
            dd($form);
            $hash = $encoder->encodePassword($user, $user->getPassword());
            
            $user->setPassword($hash);
            $user->setUpdatedAt(new \DateTime('now'));
            
            $manager->flush();

            $this->addFlash('success', 'Votre profil à été mis à jour.');

            return $this->redirectToRoute('security_user_profile');
        }

        return $this->render('security/user_profile.html.twig', [
            'user'  => $user,
            'galeries' => $galery,
            'form' => $form->createView(),
        ]);
    } 
}
