<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangeEmailType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'password', 'password',
            array(
                'label' => 'form.password',
                'required' => true,
                'translation_domain' => 'UserChange',
                'label_attr' => array('class'=>'col-lg-3 control-label'),
            )
        );

        $builder->add(
            'email', 'repeated',
            array(
                'type' => 'email',
                'required' => true,
                'translation_domain' => 'UserChange',
                'first_options' => array(
                    'label' => 'form.email',
                    'label_attr' => array('class'=>'col-lg-3 control-label'),
                ),
                'invalid_message' => 'form.email.mismatch',
                'second_options' => array(
                    'label' => 'form.email.repeat',
                    'label_attr' => array('class'=>'col-lg-3 control-label'),
                )
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\ChangeEmail',
        ));
    }

    public function getName()
    {
        return 'change_email';
    }
}
