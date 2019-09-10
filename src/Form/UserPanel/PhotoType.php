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
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints\File;


/**
 * Form used for editing Users photo. 
 */
class PhotoType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('photo', FileType::class, [
            'required' => false,
            'label' => false,
            'mapped' => false,
            'help' => 'Up to 2MB',
            'constraints' => [
                new Image(),
                new File([
                    'maxSize' => '2M'
                ]),
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
            'validation_groups' => ['photo'],
        ]);
    }

}