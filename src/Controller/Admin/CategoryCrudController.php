<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Category::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'name']);
    }

    public function configureFields(string $pageName): iterable
    {
        $id = IntegerField::new('id', 'ID');
        $name = TextField::new('name');
        $galeries = AssociationField::new('galeries')->setFormTypeOptions(['disabled' => TRUE]);

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $galeries];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $galeries];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$name, $galeries];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$name, $galeries];
        }
    }
}
