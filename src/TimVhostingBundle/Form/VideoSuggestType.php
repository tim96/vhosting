<?php

namespace TimVhostingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
//use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class VideoSuggestType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array('label' => 'title.label', 'attr' =>
                array('label' => 'title.label', 'class' => 'form-horizontal',
                    /*'placeholder' => 'Title' // placeholder example*/
            ), 'required' => true))
            ->add('userName', null, array('label' => 'username.label',
                'required' => true))
            ->add('email', 'email', array('required' => false, 'label' => 'email.label',
                'attr' => array('type' => 'email')))
            ->add('link', 'url', array('required' => true, 'label' => 'link.label'))
            ->add('description', null, array('label' => 'description.label'))
            ->add('tags', null, array('label' => 'tags.label'
                // 'placeholder' => 'No tag selected'
            ))
            ->add('captcha', 'captcha', array('label' => 'captcha.label', 'charset' => '0123456789'))
            ->add('save', 'submit', array('label' => 'save.button.label'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    /*public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimVhostingBundle\Entity\VideoSuggest',
            'translation_domain' => 'TimVhostingBundle'
        ));
    }*/

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TimVhostingBundle\Entity\VideoSuggest',
            'translation_domain' => 'TimVhostingBundle'
        ));
    }

    /**
     * @return string
     */
    /*public function getName()
    {
        return 'timvhostingbundle_videosuggest';
    }*/
}
