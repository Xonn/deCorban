<?php

namespace App\Controller;

use App\Repository\GaleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Galery;
use App\Repository\CategoryRepository;

class GaleryController extends AbstractController
{
    /**
     * @Route("/galeries", name="galeries")
     * @param GaleryRepository $GaleryRepository
     * @param CategoryRepository $CategoryRepository
     */
    public function index(GaleryRepository $GaleryRepository, CategoryRepository $CategoryRepository)
    {
        $galeries = $GaleryRepository->findAll();
        $categories = $CategoryRepository->findAll();

        return $this->render('galery/index.html.twig', [
            'galeries' => $galeries,
            'categories' => $categories,
        ]);
    }

    /**
    * @Route("/galery/{id}", name="galery.show")
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
     * @Route("/galery/{id}", name="galery.show")
     * @param GaleryRepository $repository
     * @return Response
     */
    /*public function show(int $id, GaleryRepository $repository) {
        $galery = $repository->find($id);
        return $this->render('galery/show.html.twig', [
            'galery'    => $galery,
        ]);
    }*/
}
