<?php

namespace App\Form\Type;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeedbackForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Контактное лицо',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Email для связи',
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label_attr' => ['class' => 'fg-label'],
                'label' => 'Сообщение',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
            'validation_groups' => [],
        ]);
    }
}