<?php

namespace App\Controller;

use App\Repository\GaleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param GaleryRepository $repository
     * @return Response
     */
    public function index(GaleryRepository $repository): Response
    {
        $galery = $repository->findLatest();
        return $this->render('home/index.html.twig', [
            'galeries' => $galery,
            'title' => 'Decorban',
            'subtitle' => 'L\'éromantisme',
            'last_galeries' => 'Les dernières galeries'
        ]);
    }
}
