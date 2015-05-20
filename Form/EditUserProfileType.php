<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditUserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', 'choice', array(
                'choices'   => array('male' => 'Male', 'female' => 'Female', 'other' => 'Not defined'),
            ))
            ->add('seeking', 'choice', array(
                'choices'   	=> array("men" => "Men",  "women" => "Women", "both" => "Men and women"),
                'empty_value' 	=> 'form.seeking.empty_value','required' => false,
                'label'			=> 'form.seeking.label'
            ))
            ->add('youWant', 'textarea',array('required'=>false))
            ->add('about', 'textarea',array('required' => false))
        ;

        if (array_key_exists('birthday', $options)) {
			$builder->add('birthday', 'hidden', array('required' => true,'data'=>$options['birthday']));
        } else{
        	$builder->add('birthday', 'birthday', array(
			    'widget' => 'choice',
			    // this is actually the default format for single_text
			    'format' => 'yyyy-MM-dd',
        		'required' => true));
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\UserProfile',
        ));
        $resolver->setOptional(array(
            'birthday'
        ));
        $resolver->setDefaults([
            'translation_domain' => 'UserRegistration',
        ]);
    }


    public function getName()
    {
        return 'social_profile';
    }
}