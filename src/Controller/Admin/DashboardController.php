<?php

namespace App\Controller\Admin;

use App\Entity\BigSlider;
use App\Entity\User;
use App\Entity\Model;
use App\Entity\Galery;
use App\Entity\Comment;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="easyadmin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(GaleryCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('deCorban Admin');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('dd/MM/yyyy')
            ->setDateTimeFormat('dd/MM/yyyy à HH:mm')
            ->setTimeFormat('HH:mm')
            ->setFormThemes(['admin/form.html.twig', '@EasyAdmin/crud/form_theme.html.twig']);
    }

    public function configureMenuItems(): iterable
    {
        $entities = [
            MenuItem::linkToCrud('Liste des Galeries', 'far fa-list-alt', Galery::class)->setDefaultSort(['createdAt' => 'DESC']),
            MenuItem::linkToCrud('Liste des Catégories', 'far fa-list-alt', Category::class),
            MenuItem::linkToCrud('Liste des Modèles', 'far fa-list-alt', Model::class),
        ];

        yield MenuItem::linkToCrud('BigSlider', 'fas fa-images', BigSlider::class);
        yield MenuItem::subMenu('Contenus', 'fas fa-folder')->setSubItems($entities);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comments', Comment::class);
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            ->addCssFile('css/admin.css')
            ->addJsFile('js/admin.js')
            ->addCssFile('css/filepond.min.css')
            ->addCssFile('css/filepond-plugin-image-preview.min.css')
            ->addJsFile('js/filepond.min.js')
            ->addJsFile('js/filepond.jquery.js')
            ->addJsFile('js/filepond-plugin-image-preview.min.js');
    }
}
