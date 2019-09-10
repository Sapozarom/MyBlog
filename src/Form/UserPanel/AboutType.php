<?php

namespace App\Form\UserPanel;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\User;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Length;

/**
 * Form used for editing Users about section. 
 */
class AboutType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('about', TextareaType::class, [
            'label' => false,
            'help' => 'Up to 1000 characters',
            'constraints' => [
                new Length([
                    'max'=> 1000,
                    'maxMessage' => "Ths section cannot be longer than {{ limit }} characters",
                    
                ])
            ]
        ]);
        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'label' => false,
        ]);
    }

}