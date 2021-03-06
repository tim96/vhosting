<?php declare(strict_types=1);

namespace App\TimVhostingBundle\Form;

use App\TimVhostingBundle\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Gregwar\CaptchaBundle\Type\CaptchaType;

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
            ->add('email', EmailType::class, array('label' => 'feedback.email.label'))
            ->add('message', null, array('label' => 'feedback.message.label'))
            ->add('captcha', CaptchaType::class, array('label' => 'captcha.label', 'charset' => '0123456789'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Feedback::class,
            'translation_domain' => 'TimVhostingBundle'
        ));
    }
}
