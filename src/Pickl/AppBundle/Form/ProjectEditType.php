<?php

namespace Pickl\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProjectEditType extends AbstractType
{
    public function getParent()
    {
        return ProjectType::class;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('picture')
            ->add('picture', ImageType::class, array('required' => false))
            ->remove('counterparts')
            ->add('counterparts', CollectionType::class, array(
                'entry_type' => CounterpartType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ))
            ;
    }
}
