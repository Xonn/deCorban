<?php

namespace App\Controller\Admin;

use App\Entity\Galery;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
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
            ->setSearchFields(['id', 'title', 'description', 'thumbnail', 'slug']);
    }

    public function configureFields(string $pageName): iterable
    {
        $title = TextField::new('title');
        $thumbnailFile = ImageField::new('thumbnailFile')->setFormType(VichImageType::class);
        $thumbnail = ImageField::new('thumbnail')->setBasePath('/upload/image');
        $description = TextareaField::new('description');
        $categories = AssociationField::new('categories')->setFormTypeOptions(['by_reference' => FALSE]);
        $models = AssociationField::new('models')->setFormTypeOptions(['by_reference' => FALSE]);
        $pictureFiles = Field::new('pictureFiles');
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
            return [$id, $title, $description, $thumbnail, $createdAt, $updatedAt, $slug, $isFree, $categories, $models, $comments, $images, $pictures, $userLikes];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$title, $thumbnailFile, $description, $categories, $models, $pictureFiles, $isFree, $createdAt, $updatedAt, $comments];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$title, $thumbnailFile, $description, $categories, $models, $pictureFiles, $isFree, $createdAt, $updatedAt, $comments];
        }
    }
}
