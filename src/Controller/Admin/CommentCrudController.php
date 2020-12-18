<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'message']);
    }

    public function configureFields(string $pageName): iterable
    {
        $mainPanel = FormField::addPanel('Main Informations')->setIcon('fas fa-edit')->addCssClass('col-md-8 float-left');
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')->addCssClass('col-md-4 float-right');
        $message = TextareaField::new('message');
        $user = AssociationField::new('user')->setFormTypeOptions(['disabled' => true]);
        $galery = AssociationField::new('galery')->setFormTypeOptions(['disabled' => true]);
        $replyTo = AssociationField::new('replyTo')->setFormTypeOptions(['disabled' => true]);
        $comments = AssociationField::new('comments');
        $isPublished = BooleanField::new('isPublished');
        $createdAt = DateTimeField::new('createdAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $updatedAt = DateTimeField::new('updatedAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $user, $galery, $replyTo, $createdAt, $isPublished];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $message, $createdAt, $updatedAt, $isPublished, $user, $galery, $replyTo];
        } elseif (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            return [$mainPanel, $message, $user, $galery, $replyTo, $advancedSettingsPanel, $isPublished, $createdAt, $updatedAt];
        }
    }
}
