<?php

namespace App\Controller\Admin;

use App\Entity\Galery;
use App\Form\PictureType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class GaleryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Galery::class;
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
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')->addCssClass('col-md-4 float-right');
        $title = TextField::new('title');
        $thumbnailFile = ImageField::new('thumbnailFile')->setFormType(VichImageType::class)->setFormTypeOptions(['allow_delete' => false, 'required' => (Crud::PAGE_NEW === $pageName ? true : false)])->addCssClass('preview hide required');
        $thumbnail = ImageField::new('thumbnail')->setBasePath('/upload/image');
        $description = TextEditorField::new('description');
        $categories = AssociationField::new('categories')->setFormTypeOptions(['by_reference' => FALSE]);
        $models = AssociationField::new('models')->setFormTypeOptions(['by_reference' => FALSE]);
        $pictureFiles = Field::new('pictureFiles')->addCssClass('hide');
        $isFree = BooleanField::new('isFree');
        $createdAt = DateTimeField::new('createdAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy');
        $updatedAt = DateTimeField::new('updatedAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy');
        $comments = AssociationField::new('comments');
        $id = IntegerField::new('id', 'ID');
        $slug = TextField::new('slug');
        $images = AssociationField::new('images');
        $pictures = AssociationField::new('pictures');
        $userLikes = AssociationField::new('userLikes');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $title, $thumbnail, $createdAt, $updatedAt, $userLikes, $isFree];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$mainPanel, $id, $title, $description, $thumbnail, $createdAt, $updatedAt, $slug, $isFree, $categories, $models, $comments, $images, $pictures, $userLikes];
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            return [$mainPanel, $title, $description, $categories, $models, $thumbnailPanel, $thumbnailFile, $advancedSettingsPanel, $isFree, $createdAt, $updatedAt, $imagesPanel, $pictureFiles];
        }
    }
}
