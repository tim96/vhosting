<?php

namespace TimVhostingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoSuggestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('attr' => array(
                'class' => 'form-horizontal'
            ), 'required' => true))
            ->add('userName', null, array('label' => 'Username',
                'required' => true))
            ->add('email')
            ->add('link')
            ->add('description')
            // ->add('createdAt')
            ->add('tags')
            ->add('save', 'submit', array('label' => 'Send'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimVhostingBundle\Entity\VideoSuggest'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'timvhostingbundle_videosuggest';
    }
}