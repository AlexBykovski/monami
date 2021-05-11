<?php

namespace App\Form\Type;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use App\Entity\VacancyBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VacancyBlockForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', CKEditorType::class, ['label' => 'Заголовок:', 'required'=>true,
                'attr' => array('style' => 'width:100%;height:200px;',  'class'=>'ckeditor')])
            ->add('description', CKEditorType::class, ['label' => 'Описание:', 'required'=>true,
                'attr' => array('style' => 'width:100%;height:200px;', 'class'=>'ckeditor')])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VacancyBlock::class,
            'validation_groups' => [],
        ]);
    }
}
