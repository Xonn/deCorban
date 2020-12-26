<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileFormType;
use App\Form\UpdatePasswordFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // If user is already connected.
        if ($this->getUser()) {
            return $this->redirectToRoute('security_user_profile');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(){}
    
    /**
     * @Route("/mon-compte/", name="security_user_profile")
     */
    public function userProfile(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        // If user is not connected
        if (!$user = $this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $formPassword = $this->createForm(UpdatePasswordFormType::class, $user, ['validation_groups' => ['User']]);
        $formProfile = $this->createForm(ProfileFormType::class, $user);

        $action = $request->get('action');
        if ('profile' === $action) {
            $formProfile->handleRequest($request);
        } elseif ('password' === $action) {
            $formPassword->handleRequest($request);
        }
        
        // Password update.
        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $data = $formPassword->getData();
            $user->setPassword($encoder->encodePassword($user, $data->getPassword()));
            $user->setUpdatedAt(new \DateTime('now'));
            $manager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été mis à jour');

            return $this->redirectToRoute('security_user_profile');
        }

        // Email & avatar update.
        if ($formProfile->isSubmitted() && $formProfile->isValid()) {
            $user->setUpdatedAt(new \DateTime('now'));
            $manager->flush();

            $this->addFlash('success', 'Votre profil a bien été mis à jour');

            return $this->redirectToRoute('security_user_profile');
        }


        return $this->render('security/user_profile.html.twig', [
            'user'  => $user,
            'galeries' => $user->getLikedGaleries(),
            'form_profile' => $formProfile->createView(),
            'form_password' => $formPassword->createView(),
        ]);
    }

    /**
    * @Route("/membre/{username}", name="user.show")
    * @param User $user
    * @param CommentRepository $commentRepository
    * @return Response
    */
    public function show(User $user, CommentRepository $commentRepository) 
    {
        $comments = $commentRepository->findBy(['user' => $user->getId()], ['createdAt' => 'DESC'], 3);

        return $this->render('security/user_show.html.twig', [
            'user' => $user,
            'galeries' => $user->getLikedGaleries(),
            'comments' => $comments
        ]);
    }
}
