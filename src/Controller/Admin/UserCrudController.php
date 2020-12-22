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
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;

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
        $premiumPanel = FormField::addPanel('Premium')->setIcon('fab fa-stripe-s')->addCssClass('col-md-8 float-left');
        $avatarPanel = FormField::addPanel('Avatar', 'fas fa-user-circle')->addCssClass('col-md-4 float-right required');
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')
                                    ->addCssClass('col-md-4 float-right');
        $email = TextField::new('email');
        $username = TextField::new('username');
        $imageFile = ImageField::new('imageFile')
                        ->setFormType(VichImageType::class)
                        ->setFormTypeOptions(['allow_delete' => false, 'required' => (Crud::PAGE_NEW === $pageName ? true : false)])
                        ->addCssClass('preview hide required');
        $image = ImageField::new('image')
                    ->setBasePath($this->getParameter('path.avatar'));
        $roles = ChoiceField::new('roles')
                    ->allowMultipleChoices()
                    ->setChoices(['User' => 'ROLE_USER', 'Administrator' => 'ROLE_ADMIN']);
        $isVerified = BooleanField::new('isVerified');
        $createdAt = DateTimeField::new('createdAt')
                        ->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $updatedAt = DateTimeField::new('updatedAt')
                        ->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $comments = AssociationField::new('comments')->setFormTypeOptions(['disabled' => true]);
        $id = IntegerField::new('id', 'ID');
        $password = TextField::new('password');
        $likedGaleries = AssociationField::new('likedGaleries');
        $premium = AssociationField::new('payments')
            ->setFormTypeOptions(['disabled' => true])
            ->setHelp('Identifiant de paiement généré par Stripe');
        $stripeId = TextField::new('stripeId')
            ->setFormTypeOptions(['disabled' => true])
            ->setHelp('Identifiant d\'utilisateur généré par Stripe');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $username, $email, $image, $roles, $createdAt, $isVerified];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $email, $username, $password, $roles, $image, $createdAt, $updatedAt, $isVerified, $comments, $likedGaleries];
        } elseif (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            return [$mainPanel, $email, $username, $roles, $comments, $avatarPanel, $imageFile, $premiumPanel, $stripeId, $premium, $advancedSettingsPanel, $isVerified, $createdAt, $updatedAt];
        }
    }
}
