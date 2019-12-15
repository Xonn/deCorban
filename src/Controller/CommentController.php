<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Galery;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    /**
     * @Route("/send-comment/{galery}", name="send_comment", options={"expose"=true})
     * @param Galery $galery
     */
    public function sendComment(Request $request, ObjectManager $manager, Galery $galery)
    {

        // If user is not connected
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $user = $this->getUser();

        $comment = new Comment();

        $form = $this->createForm(CommentType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            //test
            $comment->setGalery($galery);
            //dd($request);
            $comment->setUser($user);
            //$comment->setGalery();
            $comment->setCreatedAt(new \DateTime('now'));
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire à été ajouté !');

            return $this->redirectToRoute('galery.show', ['id' => 1]);
        }

        return $this->render('comment/form.html.twig',  [
            'form' => $form->createView(),
            'galeryId' => $galery->getId()
        ]);
    } 
}