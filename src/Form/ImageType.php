<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('image');
        //$builder->add('image', FileType::class, ['mapped' => FALSE, 'attr' => ['multiple' => 'multiple']]);
        $builder->add('imageFile', VichFileType::class, ['attr' => [
            'accept' => 'image/*',
            'multiple' => 'multiple',
        ]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
