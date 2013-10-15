<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 1/10/13
 * Time: 17:37
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
            ->add('email','email')
            ->add('password','password');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Ant\Bundle\ChateaClientBundle\Model\User'
            ));
    }

    public function getName()
    {
        return 'api_user';
    }

}