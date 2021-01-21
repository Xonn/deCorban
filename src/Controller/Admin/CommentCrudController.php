<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
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

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'message']);
    }

    public function configureFields(string $pageName): iterable
    {
        $message = TextareaField::new('message');
        $user = AssociationField::new('user')->setFormTypeOptions(['disabled' => true]);
        $galery = AssociationField::new('galery')->setFormTypeOptions(['disabled' => true]);
        $replyTo = AssociationField::new('replyTo')->setFormTypeOptions(['disabled' => true]);
        $isPublished = BooleanField::new('isPublished');
        $createdAt = DateTimeField::new('createdAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $updatedAt = DateTimeField::new('updatedAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => true]);
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $user, $galery, $replyTo, $createdAt, $isPublished];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $message, $createdAt, $updatedAt, $isPublished, $user, $galery, $replyTo];
        }
    }
}
