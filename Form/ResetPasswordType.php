<?php
/**
 * Created by PhpStorm.
 * User: ant4
 * Date: 13/03/15
 * Time: 10:39
 */

namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Beelab\Recaptcha2Bundle\Validator\Constraints\Recaptcha2;

class ResetPasswordType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('required' => true))
            ->add('recaptcha', 'beelab_recaptcha2', array(
                'label' => false,
                'mapped' => false,
                'constraints' => new Recaptcha2(array('message' => 'invalid.recaptcha')),
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Form\Model\ResetPassword',
        ));
    }

    public function getName()
    {
        return 'reset_password';
    }
}