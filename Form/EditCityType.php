<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Ant\Bundle\ChateaClientBundle\Form\Transformer\CityLocationTransformer;

use Ant\Bundle\ChateaClientBundle\Form\Transformer\CountryLocationTransformer;
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

        $countryLocationTransformer = new CountryLocationTransformer($options['countryLocationManager']);
        $cityLocationTransformer = new CityLocationTransformer($options['cityLocationManager']);

        $builder->add(
            'searchCountry',
            'choice',
            array(
                'required' => true,
                'mapped' => false,
                'empty_value' => '',
                'choices' => $this->buildCountriesOptions($options['countries'])
            ));

        $builder->add($builder->create('country', 'hidden',   array('required' => true, 'attr' => array('data-city-finder' => 'current_country')))
            ->addModelTransformer($countryLocationTransformer)
        );

        $builder->add(  $builder->create('city','hidden', array('required' => false, 'attr' => array('data-city-finder' => 'current_city')))
            ->addModelTransformer($cityLocationTransformer)
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Form\Model\EditCity'
        ));

        $resolver->setRequired(array(
            'countryLocationManager', 'cityLocationManager', 'countries'
        ));

        $resolver->setAllowedTypes(array(
            'countryLocationManager' => 'Ant\Bundle\ChateaClientBundle\Manager\CountryManager',
            'cityLocationManager' => 'Ant\Bundle\ChateaClientBundle\Manager\CityManager'
        ));
    }

    public function getName()
    {
        return 'edit_city';
    }

    private function buildCountriesOptions($countries)
    {
        $options = array();

        foreach($countries as $country){;
            $options[$country['value']] = $country['name'];
        }

        return $options;
    }
}