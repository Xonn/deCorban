<?php

namespace App\Controller\Admin;

use App\Entity\Payment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;

class PaymentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Payment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'user', 'galery', 'type', 'startDate', 'endDate', 'pId'])
            ->setFormOptions(['attr' => ['class' => 'row d-block clearfix']]);
    }

    public function configureFields(string $pageName): iterable
    {
        $mainPanel = FormField::addPanel('Main Informations')->setIcon('fas fa-edit')
                        ->addCssClass('col-md-8 float-left');

        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')
                                    ->addCssClass('col-md-4 float-right');
        $id = IntegerField::new('id', 'ID');
        $user = AssociationField::new('user')->setFormTypeOptions(['disabled' => true]);
        $galery = AssociationField::new('galery')->setFormTypeOptions(['disabled' => true]);
        $type = ChoiceField::new('type')->setChoices(['Subscription' => 'subscription', 'Rent' => 'rent'])->setFormTypeOptions(['disabled' => true])->setTemplatePath('admin/fields/status.html.twig');
        $startDate = DateTimeField::new('startDate');
        $endDate = DateTimeField::new('endDate');
        $pId = TextField::new('pId')
            ->setFormTypeOptions(['disabled' => true])
            ->setTemplatePath('admin/fields/link.html.twig')
            ->setHelp('Identifiant de paiement généré par Stripe')
            ->formatValue(function ($value) {
                return ['link' => 'https://dashboard.stripe.com/payments/' . $value, 'external' => true];
            });
        $status = Field::new('isPremium', 'Status')
            ->setTemplatePath('admin/fields/status.html.twig')
            ->formatValue(function ($value) {
                return $value == true ? 'Actif' : 'Expiré';
            });

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $user, $type, $galery, $startDate, $endDate, $pId, $status];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $user, $type, $galery, $pId, $startDate, $endDate];
        } elseif (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            return [$mainPanel, $user, $type, $galery, $pId, $advancedSettingsPanel, $startDate, $endDate];
        }
    }
}
