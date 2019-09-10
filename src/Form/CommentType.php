<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Comment;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

/**
 * Form used for adding and editing comments
 */
class CommentType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', CKEditorType::class, [
                'label' => false,
                'help' => 'up to 1000 characters' ,          
                'config' => [
                    'toolbar' => 'basic'
                ]
                
            ]);

        
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }

}