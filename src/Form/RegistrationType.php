<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


/**
 * Overridden FOSUB registration form
 */
class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'help' => 'Full name should contain at least 5 alphanumeric characters',
        ]);
        
        //remove plainPassword field from parent
        $builder->remove('plainPassword');
        
        //add password field with proper help message
        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'options' => array(
                'translation_domain' => 'FOSUserBundle',
                'attr' => array(
                    'autocomplete' => 'new-password',
                ),
            ),
            'first_options' => array(
                'label' => 'form.password',
                'help' => ' Password should contain between 8 and 18 characters'
            ),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
   {
       return $this->getBlockPrefix();
   }

    
}