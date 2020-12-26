<?php

namespace App\Controller;

use App\Entity\Galery;
use App\Repository\GaleryRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class GaleryController extends AbstractController
{
    /**
     * @Route("/galeries", name="galeries")
     * @param GaleryRepository $GaleryRepository
     * @param CategoryRepository $CategoryRepository
     */
    public function index(GaleryRepository $GaleryRepository, CategoryRepository $CategoryRepository)
    {
        $galeries = $GaleryRepository->findBy(['isPublished' => true]);
        $categories = $CategoryRepository->findAll();

        return $this->render('galery/index.html.twig', [
            'galeries' => $galeries,
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/galerie/{slug}", name="galery.show")
    * @param Galery $galery
    * @return Response
    */
    public function show(Galery $galery, GaleryRepository $repository) 
    {
        $categories = $galery->getCategories();
        
        $related_galeries = $repository->findByCategory($categories);
        return $this->render('galery/show.html.twig', ['galery' => $galery, 'related_galeries' => $related_galeries]);
    }

    /**
     * @param GaleryRepository $GaleryRepository
     */
    public function popularGaleries(GaleryRepository $repository) {
        $galeries = $repository->findPopular();

        return $this->render('galery/_popular.html.twig', [
            'galeries' => $galeries
        ]);
    }

    /**
    * @Route("/like/{galery}/", name="galery.like", options={"expose"=true})
    * @param Galery $galery
    * @return Response
    */
    public function like(Galery $galery, EntityManagerInterface $manager, Request $request) {
        // If user is not connected, abord.
        if (!$this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->getMethod() === 'POST' && $request->isXmlHttpRequest()) {
            
            // If galery doesn't exsit, return empty response.
            if (!$galery) {
                return new JsonResponse();
            }
            
            $csrfToken = $request->request->get('csrfToken');
            
            // CHeck that the crsfToken is valid.
            if ($this->isCsrfTokenValid('galery' . $galery->getId(), $csrfToken)) {
                $user = $this->getUser();

                if ($user->isLiking($galery)) {
                    $user->removeLikedGalery($galery);
                } else {
                    $user->addLikedGalery($galery);
                }
        
                $manager->flush(); 
            }
        }
        return new JsonResponse(count($galery->getUserLikes()));
    }
}
