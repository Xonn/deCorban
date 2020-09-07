<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

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
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$email, $username, $imageFile, $roles, $isActive, $createdAt, $updatedAt, $comments];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$email, $username, $imageFile, $roles, $isActive, $createdAt, $updatedAt, $comments];
        }
    }
}
