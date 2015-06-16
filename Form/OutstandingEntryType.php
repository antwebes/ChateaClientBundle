<?php

namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OutstandingEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('from', 'datetime')
            ->add('until', 'datetime');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(           
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\OutstandingEntry',
            'csrf_protection' => false
        ));
    }

    public function getName()
    {
        return 'outstanding_entry';
    }
}