<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateUserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', 'choice', array(
                'choices'   => array('male' => 'Male', 'female' => 'Female', 'other' => 'Not defined'),
            ))
            ->add('seeking', 'choice', array(
                'choices'   	=> array("men" => "Men",  "women" => "Women", "both" => "Men and women"),
                'empty_value' 	=> 'Choose an option','required' => false,
                'label'			=> 'I am seeking'
            ))
            ->add('youWant', 'textarea',array('required'=>false))
            ->add('about', 'textarea',array('required' => false))
            ->add('birthday', 'hidden', array('required' => true,'data'=>$options['birthday']))
            ->add('image', 'file', array('required'=>false, 'mapped' => false)
            )
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile',
        ));
        $resolver->setRequired(array(
            'birthday'
        ));
    }


    public function getName()
    {
        return 'user_profile';
    }
}