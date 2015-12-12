<?php

namespace TimVhostingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array('label' => 'feedback.name.label'))
            ->add('email', 'email', array('label' => 'feedback.email.label'))
            ->add('message', null, array('label' => 'feedback.message.label'))
            ->add('captcha', 'captcha', array('label' => 'captcha.label', 'charset' => '0123456789'))
            // ->add('createdAt')
            // ->add('isAnswered')
            // ->add('isDeleted')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimVhostingBundle\Entity\Feedback',
            'translation_domain' => 'TimVhostingBundle'
        ));
    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimVhostingBundle\Entity\Feedback',
            'translation_domain' => 'TimVhostingBundle'
        ));
    }
}
