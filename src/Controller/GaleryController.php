<?php

namespace App\Controller;

use App\Repository\GaleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Galery;

class GaleryController extends AbstractController
{
    /**
     * @Route("/galeries", name="galeries")
     */
    public function index()
    {
        return $this->render('galery/index.html.twig', [
            'controller_name' => 'GaleryController',
        ]);
    }

    /**
    * @Route("/galery/{id}", name="galery.show")
    * @param Galery $galery
    * @return Response
    */
    public function show(Galery $galery) 
    {
        return $this->render('galery/show.html.twig', ['galery' => $galery]);
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
