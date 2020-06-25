<?php

namespace App\Controller;

use App\Repository\ModelRepository;
use App\Repository\GaleryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param GaleryRepository $galeryRepository
     * @param ModelRepository $modelRepository
     * @return Response
     */
    public function index(GaleryRepository $galeryRepository, ModelRepository $modelRepository): Response
    {
        $galeries = $galeryRepository->findLatest();
        $models = $modelRepository->findLatest();
        return $this->render('home/index.html.twig', [
            'galeries' => $galeries,
            'models' => $models,
            'title' => 'Decorban',
            'subtitle' => 'L\'éromantisme',
            'last_galeries' => 'Les dernières galeries'
        ]);
    }
}
