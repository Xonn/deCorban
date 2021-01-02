<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\User;
use App\Entity\Model;
use App\Entity\Galery;
use App\Entity\Comment;
use App\Entity\Category;
use App\Entity\BigSlider;
use App\Entity\Payment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private $crudUrlGenerator;

    public function __construct(CrudUrlGenerator $crudUrlGenerator)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    /**
     * @Route("/admin", name="easyadmin")
     */
    public function index(): Response
    {
        // Initalise content repository
        $galeries = $this->getDoctrine()->getRepository(Galery::class);
        $categories = $this->getDoctrine()->getRepository(Category::class);
        $models = $this->getDoctrine()->getRepository(Model::class);
        $users = $this->getDoctrine()->getRepository(User::class);
        $payments = $this->getDoctrine()->getRepository(Payment::class);
        $comments = $this->getDoctrine()->getRepository(Comment::class);

        // Set base url for content
        $base_galery_url = $this->crudUrlGenerator->build()->setController(GaleryCrudController::class);
        $base_category_url = $this->crudUrlGenerator->build()->setController(CategoryCrudController::class);
        $base_model_url = $this->crudUrlGenerator->build()->setController(ModelCrudController::class);
        $base_user_url = $this->crudUrlGenerator->build()->setController(UserCrudController::class);
        $base_payment_url = $this->crudUrlGenerator->build()->setController(PaymentCrudController::class);
        $base_comment_url = $this->crudUrlGenerator->build()->setController(CommentCrudController::class);

        $data = [];
        $data['galeries'] = [
            'name' => 'Galeries',
            'class' => 'bg-yellow',
            'icon' => 'fas fa-images',
            'base_url' => $base_galery_url,
            'list_url' => $base_galery_url->setAction(Action::INDEX)->generateUrl(),
            'count' => $galeries->count([]),
            'last' => $galeries->findBy([], ['createdAt' => 'DESC'], 5),
            'columns' => ['id' => '#', 'title' => 'Titre', 'createdAt' => 'Date de création'],
        ];
        $data['categories'] = [
            'name' => 'Catégories',
            'class' => 'bg-orange',
            'icon' => 'fas fa-stream',
            'base_url' => $base_category_url,
            'list_url' => $base_category_url->setAction(Action::INDEX)->generateUrl(),
            'count' => $categories->count([]),
            'last' => $categories->findBy([], ['id' => 'DESC'], 5),
            'columns' => ['id' => '#', 'name' => 'Titre'],
        ];
        $data['models'] = [
            'name' => 'Modèles',
            'class' => 'bg-pink',
            'icon' => 'fas fa-camera',
            'base_url' => $base_model_url,
            'list_url' => $base_model_url->setAction(Action::INDEX)->generateUrl(),
            'count' => $models->count([]),
            'last' => $models->findBy([], ['createdAt' => 'DESC'], 5),
            'columns' => ['id' => '#', 'name' => 'Nom', 'createdAt' => 'Date de création'],
        ];
        $data['users'] = [
            'name' => 'Utilisateurs',
            'class' => 'bg-purple',
            'icon' => 'fas fa-users',
            'base_url' => $base_user_url,
            'list_url' => $base_user_url->setAction(Action::INDEX)->generateUrl(),
            'count' => $users->count([]),
            'last' => $users->findBy([], ['createdAt' => 'DESC'], 5),
            'columns' => ['id' => '#', 'username' => 'Pseudo', 'createdAt' => 'Date de création'],
        ];
        $data['payments'] = [
            'name' => 'Paiements',
            'class' => 'bg-blue',
            'icon' => 'fas fa-credit-card',
            'base_url' => $base_payment_url,
            'list_url' => $base_payment_url->setAction(Action::INDEX)->generateUrl(),
            'count' => $payments->count([]),
            'last' => $payments->findBy([], ['startDate' => 'DESC'], 5),
            'columns' => ['id' => '#', 'type' => 'Type', 'startDate' => 'Date de début'],
        ];
        $data['comments'] = [
            'name' => 'Commentaires',
            'class' => 'bg-green',
            'icon' => 'fas fa-comments',
            'base_url' => $base_comment_url,
            'list_url' => $base_comment_url->setAction(Action::INDEX)->generateUrl(),
            'count' => $comments->count([]),
            'last' => $comments->findBy([], ['createdAt' => 'DESC'], 5),
            'columns' => ['id' => '#', 'message' => 'Message', 'createdAt' => 'Date de création'],
        ];
        $index = 0;
        foreach($data as $item_k => $item) {
            if (isset($item['last'])) {
                foreach($item['last'] as $entry) {
                    $data[$item_k]['display'][$index] = [];
                    foreach($item['columns'] as $key => $value) {
                        $field = $entry->get($key);
                        if ($key == 'id') {
                            $data[$item_k]['display'][$index] += [
                                'edit_url' => $item['base_url']->setAction(Action::EDIT)->setEntityId($field)->generateUrl()
                            ];
                        }

                        if (is_string($field) && (strlen($field) > 15)) {
                            $field = substr($field, 0, 12) . '...';
                        }

                        if ($item_k == 'payments' && is_string($field)) {
                            $field = $field == 'rent' ? 'Location' : 'Abonnement';
                        }

                        // Convert DateTime to formatted string
                        if ($field instanceof DateTime) {
                            $field = $field->format('d/m/Y');
                        }

                        $data[$item_k]['display'][$index] += [$key => $field];
                    }
                    $index++;
                }
            }
        }

        return $this->render('admin/dashboard.html.twig', ['data' => $data]);
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
        yield MenuItem::linktoDashboard('Dashboard', 'fas fa-tachometer-alt');

        yield MenuItem::section('Content', 'fas fa-folder');
        yield MenuItem::linkToCrud('BigSlider', 'fas fa-ellipsis-h', BigSlider::class);
        yield MenuItem::linkToCrud('Galeries', 'fas fa-images', Galery::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-stream', Category::class);
        yield MenuItem::linkToCrud('Models', 'fas fa-camera', Model::class);

        yield MenuItem::section();
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Payments', 'fas fa-credit-card', Payment::class);
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);

        yield MenuItem::section('Stripe', 'fab fa-stripe-s');
        yield MenuItem::linkToUrl('Dashboard', 'fas fa-external-link-alt', 'https://dashboard.stripe.com/');

        yield MenuItem::section();
        yield MenuItem::linktoRoute('Return to website', 'fas fa-home', 'home');
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
