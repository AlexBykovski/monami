<?php

namespace App\Form\Type;

use App\Entity\LinkImage;
use App\Helper\AdminHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkImageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helper = new AdminHelper('/images/');

        $builder
            ->add('image', FileType::class, [
                'label_attr' => ['class' => 'control-label'],
                'label' => 'Изображение',
                'required' => false,
                'mapped' => false,
            ])
            ->add('link', TextType::class, [
                'label_attr' => ['class' => 'control-label'],
                'label' => 'Описание',
                'required' => true,
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($helper) {
                $bonus = $event->getData();
                $form = $event->getForm();

                if($bonus instanceof LinkImage){
                    $config = $form->get('image')->getConfig();
                    $options = $config->getOptions();
                    $options["sonata_help"] = $bonus->getImage() ? $helper->getImagesHelp([$bonus->getImage()]) : "";

                    $form->add('image', FileType::class, $options);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => LinkImage::class,
            'validation_groups' => [],
        ]);
    }
}