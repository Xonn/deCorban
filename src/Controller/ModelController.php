<?php

namespace App\Controller;

use App\Entity\Model;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ModelController extends AbstractController
{
    /**
     * @Route("/modeles", name="models")
     * @param ModelRepository $GaleryRepository
     */
    public function index(ModelRepository $ModelRepository)
    {
        $models = $ModelRepository->findAll();

        return $this->render('model/index.html.twig', [
            'models' => $models,
        ]);
    }

    /**
    * @Route("/model/{slug}", name="model.show")
    * @param Model $model
    * @return Response
    */
    public function show(Model $model, ModelRepository $repository) 
    {
        return $this->render('model/show.html.twig', ['model' => $model]);
    }
}