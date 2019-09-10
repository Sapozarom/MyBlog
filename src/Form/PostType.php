<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Post;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\File;


/**
 * Form used for adding and editing articles
 */
class PostType extends AbstractType 
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('picture', FileType::class, [
                'required' => false,
                'help' => 'Size up to 5MB',
                'mapped'=>false,
                'constraints' => [
                    new Image(),
                    new File([
                        'maxSize' => '5M'
                    ]),
                ]
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'Content',
                'config' => [
                    'toolbar' => 'standard'
                ]                
            ])
            ->add('tagsText', TextType::class, [
                'required' => false, 
                'label' => 'Tags',
                'help' => 'Write tags with comma between, example: tag1, tag2, tag3'
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }

}