<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'name', 'description', 'age', 'height', 'country', 'city', 'image', 'slug']);
    }

    public function configureFields(string $pageName): iterable
    {
        $name = TextField::new('name');
        $imageFile = ImageField::new('imageFile')->setFormType(VichImageType::class);
        $image = ImageField::new('image')->setBasePath('/upload/model');
        $description = TextareaField::new('description');
        $age = IntegerField::new('age');
        $height = IntegerField::new('height');
        $country = TextField::new('country');
        $city = TextField::new('city');
        $galeries = AssociationField::new('galeries')->setFormTypeOptions(['disabled' => TRUE]);
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');
        $id = IntegerField::new('id', 'ID');
        $slug = TextField::new('slug');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $image, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $description, $age, $height, $country, $city, $image, $createdAt, $updatedAt, $slug, $galeries];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $imageFile, $description, $age, $height, $country, $city, $galeries, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $imageFile, $description, $age, $height, $country, $city, $galeries, $createdAt, $updatedAt];
        }
    }
}
