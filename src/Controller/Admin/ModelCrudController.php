<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class ModelCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setSearchFields(['id', 'name', 'description', 'age', 'height', 'country', 'city', 'image', 'slug'])
            ->setFormOptions(['attr' => ['class' => 'row d-block clearfix']]);
    }

    public function configureFields(string $pageName): iterable
    {
        $mainPanel = FormField::addPanel('Main Informations')->setIcon('fas fa-edit')
                        ->addCssClass('col-md-8 float-left');
        $imagePanel = FormField::addPanel('Thumbnail', 'far fa-image')
                        ->addCssClass('col-md-4 float-right required');
        $advancedSettingsPanel = FormField::addPanel('Advanced Settings', 'fas fa-cogs')
                                    ->addCssClass('col-md-4 float-right');
        $id = IntegerField::new('id', 'ID');
        $name = TextField::new('name');
        $imageFile = ImageField::new('imageFile')->setFormType(VichImageType::class)
                        ->setFormTypeOptions(['allow_delete' => false, 'required' => (Crud::PAGE_NEW === $pageName ? true : false)])
                        ->addCssClass('preview hide required');
        $image = ImageField::new('image')->setBasePath($this->getParameter('path.model_thumbnails'));
        $description = TextEditorField::new('description');
        $birthdate = DateField::new('birthDate');
        $height = IntegerField::new('height')->setHelp('Taille du modèle en centimètre.');
        $country = TextField::new('country');
        $city = TextField::new('city');
        $instagram = TextField::new('instagram');
        $galeries = AssociationField::new('galeries')->setFormTypeOptions(['disabled' => TRUE]);
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt')->setFormTypeOptions(['disabled' => true]);
        $slug = TextField::new('slug');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name, $image, $createdAt, $updatedAt];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $description, $birthdate, $height, $country, $city, $instagram, $image, $createdAt, $updatedAt, $slug, $galeries];
        } elseif (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT === $pageName) {
            return [$mainPanel, $name, $description, $birthdate, $height, $country, $city, $instagram, $imagePanel, $imageFile, $advancedSettingsPanel, $galeries, $createdAt, $updatedAt];
        }
    }
}
