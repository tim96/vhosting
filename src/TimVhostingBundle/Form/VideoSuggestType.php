<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Form;

use App\TimVhostingBundle\Entity\VideoSuggest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

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
            ->add('email', EmailType::class, array('required' => false, 'label' => 'email.label',
                'attr' => array('type' => 'email')))
            ->add('link', UrlType::class, array('required' => true, 'label' => 'link.label'))
            ->add('description', null, array('label' => 'description.label'))
            ->add('tags', null, array('label' => 'tags.label'
                // 'placeholder' => 'No tag selected'
            ))
            ->add('captcha', CaptchaType::class, array('label' => 'captcha.label', 'charset' => '0123456789'))
            // ->add('save', 'submit', array('label' => 'save.button.label'))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => VideoSuggest::class,
            'translation_domain' => 'TimVhostingBundle'
        ));
    }
}
