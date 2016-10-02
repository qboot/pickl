<?php

namespace Pickl\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,

                function(FormEvent $event) {

                    $image = $event->getData();

                    if(null === $image) {
                        $event->getForm()->add('file', FileType::class,array('label' => false, 'required' => true));
                        return;
                    }

                    if(!empty($image->getUrl())) {
                        $image->setHiddenUrl($image->getUrl());
                        $event->getForm()->add('file', FileType::class, array('label' => false, 'required' => false, 'image_path' => 'webPath'));
                        $event->getForm()->add('hiddenUrl', HiddenType::class);
                    } else {
                        $event->getForm()->add('file', FileType::class,array('label' => false));
                    }
                }
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pickl\AppBundle\Entity\Image'
        ));
    }
}
