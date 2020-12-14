<?php

namespace App\Controller\Admin;

use App\Entity\BigSlider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BigSliderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BigSlider::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setFormOptions(['attr' => ['class' => 'row d-block clearfix']]);
    }

    public function configureFields(string $pageName): iterable
    {
        $mainPanel = FormField::addPanel('Main Informations')->setIcon('fas fa-edit')->addCssClass('col-md-8 float-left');
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')->addCssClass('col-md-4 float-right');

        $id = IntegerField::new('id', 'ID');
        $imageFile = ImageField::new('imageFile')->setFormType(VichImageType::class)->setFormTypeOptions(['allow_delete' => false, 'required' => true])->addCssClass('preview hide required');
        $image = ImageField::new('image')->setBasePath($this->getParameter('path.big_slider'));
        $createdAt = DateTimeField::new('createdAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => TRUE]);
        $updatedAt = DateTimeField::new('updatedAt')->setCustomOption('dateTimePattern', 'dd/MM/yyyy')->setFormTypeOptions(['disabled' => TRUE]);

        if (Crud::PAGE_INDEX === $pageName) {
            return [$mainPanel, $id, $image, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$mainPanel, $id, $imageFile, $advancedSettingsPanel, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            return [$mainPanel, $imageFile, $advancedSettingsPanel, $createdAt, $updatedAt];
        }
    }
}