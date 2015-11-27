<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Ant\Bundle\ChateaClientBundle\Form\Transformer\CityLocationTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\True;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True as CaptchaTrue;

class EditCityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder->add('cityName','text',  array('required' => false));

        $cityLocationTransformer = new CityLocationTransformer($options['cityLocationManager']);

        $builder->add(  $builder->create('city','hidden', array('required' => false, 'attr' => array('data-city-finder' => 'current_city')))
            ->addModelTransformer($cityLocationTransformer)
        )->add('countryName');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Form\Model\EditCity'
        ));

        $resolver->setRequired(array(
            'cityLocationManager'
        ));

        $resolver->setAllowedTypes(array(
            'cityLocationManager' => 'Ant\Bundle\ChateaClientBundle\Manager\CityManager'
        ));
    }

    public function getName()
    {
        return 'edit_city';
    }
}