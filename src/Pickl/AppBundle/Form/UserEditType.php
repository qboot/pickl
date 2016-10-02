<?php

namespace Pickl\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserEditType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, array('required' => false))
            ->add('name', TextType::class, array('required' => false))
            ->add('smallDescription', TextareaType::class, array('required' => false))
            ->add('bigDescription', TextareaType::class, array('required' => false))
            ->add('profilePicture', ImageType::class, array('required' => false))
            ->add('coverPicture', ImageType::class, array('required' => false))
            ->add('country', CountryType::class, array('required' => false))
            ->add('facebook', UrlType::class, array('required' => false))
            ->add('twitter', UrlType::class, array('required' => false))
            ->add('website', UrlType::class, array('required' => false))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pickl\AppBundle\Entity\User'
        ));
    }
}
