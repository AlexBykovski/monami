<?php

namespace App\Form\Type;

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
            ->add('title', TextType::class, [
                'label_attr' => ['class' => 'control-label'],
                'label' => 'Заголовок',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label_attr' => ['class' => 'control-label'],
                'label' => 'Описание',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => VacancyBlock::class,
            'validation_groups' => [],
        ]);
    }
}