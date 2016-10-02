<?php

namespace Pickl\AppBundle\Form;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('requestedAmount', IntegerType::class, array('scale' => 0))
            ->add('smallDescription', TextareaType::class)
            ->add('bigDescription', TextareaType::class)
            ->add('duration', ChoiceType::class, array(
                'choices' => array(
                    '15 days' => 15,
                    '30 days' => 30,
                    '45 days' => 45,
                    '60 days' => 60,
                    '90 days' => 90
                )
            ))
            ->add('country', CountryType::class)
            ->add('picture', ImageType::class)
            ->add('category', EntityType::class, array(
                'class' => 'PicklAppBundle:Category',
                'choice_label'  => 'name',
                'multiple' => false
            ))
            ->add('tags', EntityType::class, array(
                'class' => 'PicklAppBundle:Tag',
                'choice_label'  => 'word',
                'multiple' => true,
                'expanded' => false
            ))
            ->add('counterparts', CollectionType::class, array(
                'entry_type' => CounterpartType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => false
            ))
            ->add('published', CheckboxType::class, array('required' => false))
        ;

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function(Event $event) {
                    $project = $event->getData();

                    if(null === $project)
                        return;

                    if($project->getPublished()) {
                        $event->getForm()->remove('requestedAmount');
                        $event->getForm()->remove('duration');
                        $event->getForm()->remove('category');
                        $event->getForm()->remove('published');
                    }
                }
            );
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Pickl\AppBundle\Entity\Project'
        ));
    }
}
