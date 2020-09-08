<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'email', 'username', 'roles', 'image']);
    }

    public function configureFields(string $pageName): iterable
    {
        $mainPanel = FormField::addPanel('Main Informations')->setIcon('fas fa-edit')->addCssClass('col-md-8 float-left');
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')->addCssClass('col-md-4 float-right');
        $email = TextField::new('email');
        $username = TextField::new('username');
        $imageFile = ImageField::new('imageFile')->setFormType(VichImageType::class);;
        $image = ImageField::new('image')->setBasePath('/upload/user/avatar');
        $roles = ArrayField::new('roles');
        $isActive = BooleanField::new('isActive');
        $createdAt = DateTimeField::new('createdAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy');
        $updatedAt = DateTimeField::new('updatedAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy');
        $comments = AssociationField::new('comments');
        $id = IntegerField::new('id', 'ID');
        $password = TextField::new('password');
        $likedGaleries = AssociationField::new('likedGaleries');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $username, $email, $image, $roles, $createdAt, $updatedAt, $isActive];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $email, $username, $password, $roles, $image, $createdAt, $updatedAt, $isActive, $comments, $likedGaleries];
        } elseif (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            return [$mainPanel, $email, $username, $imageFile, $comments, $advancedSettingsPanel, $roles, $isActive, $createdAt, $updatedAt];
        }
    }
}
