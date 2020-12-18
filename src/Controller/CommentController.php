<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Galery;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends AbstractController
{
    /**
     * @Route("/form-comment/{galery}/", name="form_comment", options={"expose"=true})
     * @Route("/form-comment/{galery}/{replyTo}", defaults={"replyTo" = 0}, name="form_comment", options={"expose"=true})
     * @param Galery $galery
     * @param Comment $replyTo
     */
    public function formComment(Galery $galery, Comment $replyTo = null) 
    {
        $form = $this->createForm(CommentType::class);
        $replyId = null;

        if ($replyTo) {
            $replyId = $replyTo->getId();
        }

        return $this->render('comment/form.html.twig',  [
            'form' => $form->createView(),
            'galeryId' => $galery->getId(),
            'replyTo' => $replyId
        ]);
    }

    /**
     * @Route("/send-comment/{galery}/", name="send_comment", options={"expose"=true})
     * @Route("/send-comment/{galery}/{replyTo}", defaults={"replyTo" = 0}, name="send_comment", options={"expose"=true})
     * @param Galery $galery
     * @param Comment $replyTo
     */
    public function sendComment(Request $request, EntityManagerInterface $manager, Galery $galery, Comment $replyTo = null)
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(CommentType::class);
        $user = $this->getUser();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csrfToken = $request->request->get('comment')['_token'];
            
            // Check that the crsfToken is valid.
            if ($this->isCsrfTokenValid('comment_form', $csrfToken)) {
                $comment = $form->getData();
                
                $comment->setGalery($galery)
                        ->setUser($user);
                
                if ($replyTo) {
                    $comment->addReplyTo($replyTo);
                }

                $manager->persist($comment);
                $manager->flush();    
            } else {
                return new JsonResponse();
            }
        }
        
        //$this->addFlash('success', 'Votre commentaire à été ajouté !');
        $view = $this->renderView('comment/_single_comment.html.twig', [
            'comment' => $comment,
            'user'    => $user,
            'reply'   => isset($replyTo),
            'galery'  => $galery
        ]);

        return new JsonResponse(['view' => $view, 'reply' => isset($replyTo)]);
    }
}