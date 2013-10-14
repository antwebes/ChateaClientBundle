<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 1/10/13
 * Time: 18:08
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserRegisterFormType  extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username');
        $builder->add('email', 'email');
        $builder->add('plainPassword', 'repeated',
                array(
                'first'  => 'password',
                'second' => 'confirm',
                'type'        => 'password',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Ant\Bundle\ChateaClientBundle\Model\UserRegister'
            ));
    }

    public function getName()
    {
        return 'user_registration';
    }
}