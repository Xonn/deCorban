<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ModelRepository;
use App\Repository\GaleryRepository;
use App\Repository\BigSliderRepository;
use App\Notification\ContactNotification;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param GaleryRepository $galeryRepository
     * @param ModelRepository $modelRepository
     * @param BigSliderRepository $bigSliderRepository
     * @return Response
     */
    public function home(GaleryRepository $galeryRepository, ModelRepository $modelRepository, BigSliderRepository $bigSliderRepository): Response
    {
        $galeries = $galeryRepository->findLatest();
        $models = $modelRepository->findLatest();
        $slides = $bigSliderRepository->findAll();

        return $this->render('page/home.html.twig', [
            'slides' => $slides,
            'galeries' => $galeries,
            'models' => $models,
            'title' => 'Decorban',
            'subtitle' => 'L\'éromantisme',
            'last_galeries' => 'Les dernières galeries'
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, ContactNotification $notification): Response
    {
        $contact = new Contact();
        if ($this->getUser()) {
            $contact->setEmail($this->getUser()->getEmail());
        }
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre message a bien été envoyé !');
            return $this->redirectToRoute('contact');
        }

        return $this->render('page/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/a-propos", name="about")
     */
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="legal_notices")
     */
    public function legalNotices(): Response
    {
        return $this->render('page/legal_notices.html.twig');
    }
}
