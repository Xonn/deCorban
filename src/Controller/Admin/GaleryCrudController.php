<?php

namespace App\Controller\Admin;

use App\Entity\Galery;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class GaleryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Galery::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $frontView = Action::new('frontView', 'Voir')
        ->linkToRoute('galery.show', function (Galery $entity) {
            return ['slug' => $entity->getSlug()];
        });

        return $actions
            ->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER)
            ->add(Crud::PAGE_NEW, Action::SAVE_AND_CONTINUE)
            ->add(Crud::PAGE_INDEX, $frontView);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'title', 'description', 'thumbnail', 'slug'])
            ->setFormOptions(['attr' => ['class' => 'row d-block clearfix']]);
    }

    public function configureFields(string $pageName): iterable
    {
        $mainPanel = FormField::addPanel('Main Informations')->setIcon('fas fa-edit')->addCssClass('col-md-8 float-left');
        $imagesPanel = FormField::addPanel('Images', 'far fa-images')->addCssClass('col-md-8 float-left');
        $thumbnailPanel = FormField::addPanel('Thumbnail', 'far fa-image')->addCssClass('col-md-4 float-right required');
        $bannerPanel = FormField::addPanel('Banner', 'far fa-image')->addCssClass('col-md-4 float-right');
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')->addCssClass('col-md-4 float-right');
        $title = TextField::new('title');
        $thumbnailFile = ImageField::new('thumbnailFile')->setFormType(VichImageType::class)->setFormTypeOptions(['allow_delete' => false, 'required' => (Crud::PAGE_NEW === $pageName ? true : false)])->addCssClass('preview hide required');
        $bannerFile = ImageField::new('bannerFile')->setFormType(VichImageType::class)->addCssClass('preview hide');
        $thumbnail = ImageField::new('thumbnail')->setBasePath($this->getParameter('path.galery_thumbnails'));
        $description = TextEditorField::new('description')->setNumOfRows(8);
        $categories = AssociationField::new('categories')->setFormTypeOptions(['by_reference' => FALSE]);
        $models = AssociationField::new('models')->setFormTypeOptions(['by_reference' => FALSE]);
        $attachmentFiles = Field::new('attachmentFiles')
                            ->setFormTypeOptions(['block_prefix' => 'attachment_file'])
                            ->addCssClass('hide');
        if (Crud::PAGE_NEW === $pageName) {
            $attachmentFiles
                ->setHelp('Vous devez d\'abord créer la galerie avant d\'y ajouter des images. <br> Appuyez sur "Créer et continuer l\'édition" pour rester sur le formulaire actuel.')
                ->setFormTypeOptions(['disabled' => true]);
        }
        $isFree = BooleanField::new('isFree');
        $createdAt = DateField::new('createdAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy');
        $updatedAt = DateField::new('updatedAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $comments = AssociationField::new('comments');
        $id = IntegerField::new('id', 'ID');
        $slug = TextField::new('slug');
        $userLikes = AssociationField::new('userLikes');
        $cupOfCoffee = IntegerField::new('cupOfCoffee');
        $isPublished = BooleanField::new('isPublished');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $title, $thumbnail, $createdAt, $updatedAt, $userLikes,$isPublished, $isFree];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$mainPanel, $id, $title, $description, $thumbnail, $createdAt, $updatedAt, $slug, $isFree, $categories, $models, $comments, $userLikes];
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            return [$mainPanel, $title, $description, $cupOfCoffee, $categories, $models, $thumbnailPanel, $thumbnailFile, $bannerPanel, $bannerFile, $imagesPanel, $attachmentFiles, $advancedSettingsPanel, $isFree, $isPublished, $createdAt, $updatedAt];
        }
    }
}