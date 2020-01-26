<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['attr' => ['placeholder' => 'Prénom', 'class' => 'form-control']])
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Adresse email', 'class' => 'form-control']])
            ->add('subject', TextType::class, ['attr' => ['placeholder' => 'Sujet', 'class' => 'form-control']])
            ->add('message', TextareaType::class, ['attr' => ['placeholder' => 'Votre message', 'class' => 'form-control', 'maxlength' => '500']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }

}
