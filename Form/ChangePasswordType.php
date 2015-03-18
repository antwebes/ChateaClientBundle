<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'currentPassword', 'password',
            array(
                'label' => 'form.old_password',
                'required' => true,
                'translation_domain' => 'UserChange',
                'label_attr' => array('class'=>'col-lg-3 control-label'),
            )
        );

        $builder->add('plainPassword', 'repeated',
            array(
                'type' => 'password',
                'first_options' => array('label' => 'form.new_password', 'label_attr' => array('class'=>'col-lg-3 control-label')),
                'second_options' => array('label' => 'form.repeat_password', 'label_attr' => array('class'=>'col-lg-3 control-label')),
                'required' => true,
                'translation_domain' => 'UserChange',
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\ChangePassword'
        ));
    }

    public function getName()
    {
        return 'change_password';
    }
}