<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['attr' => ['placeholder' => 'Prénom', 'class' => 'form-control']])
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Adresse email', 'class' => 'form-control']])
            ->add('subject', ChoiceType::class, [
                'choices' => [
                    'Une question, une suggestion ?' => 'Question',
                    'Vous souhaitez un partenariat ?' => 'Partenariat',
                    'Si vous voulez devenir modèle, c\'est ici !' => 'Candidature'
                ],
                'attr' => [
                    'class' => 'form-control'
                    ]
                ])
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
