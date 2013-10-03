<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ant3
 * Date: 30/09/13
 * Time: 18:42
 * To change this template use File | Settings | File Templates.
 */

namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChannelFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name')
                ->add('title','text',array('required' => false))
                ->add('description','textarea',array('required'=>false));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Ant\Bundle\ChateaClientBundle\Model\Channel'
            ));
    }

    public function getName()
    {
        return 'channel';
    }

}