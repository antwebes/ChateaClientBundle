<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Ant\Bundle\ChateaClientBundle\Form\Transformer\CityLocationTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\True;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True as CaptchaTrue;

class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //xabier: cambie esto por que o nelmio repite os campos

        $builder->add(
            'email', 'email',
            array(
                'label' => 'form.email',
                'required' => true,
                'translation_domain' => 'UserRegistration',
                'label_attr' => array('class'=>'col-lg-3 control-label'),
            )
        );

        $builder->add('username', 'text',
            array(
                'required' => true,
                'label' => 'form.username',
                'translation_domain'=> 'UserRegistration',
                'label_attr'        => array('class'=>'col-lg-3 control-label'),
            )
        );

        $builder->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'UserRegistration'),
            'first_options' =>
                array(
                    'label'     => 'form.password',
                    'label_attr' => array('class'=>'col-lg-3 control-label'),
                ),
            'second_options' =>
                array(
                    'label'      => 'form.password_confirmation',
                    'label_attr' => array('class'=>'col-lg-3 control-label'),
                ),
            'invalid_message' => 'form.password.mismatch',
        ));


        $builder->add('birthday', 'birthday',
            array(
                'required' => true,
                'label'      => 'form.birthday',
                'translation_domain'=> 'UserRegistration',
                "mapped" => false,
                "years"	=> range (date('Y')-90, date('Y')-18),
                "empty_value" => '',
                "constraints" => new NotBlank(array("message" => "form.birthday.error_message")),
                'label_attr' => array('class'=>'col-lg-3 control-label')
            )
        );

        $builder->add("terms_and_conditions", "checkbox", array(
                "mapped" => false,
                'translation_domain'=> 'UserRegistration',
                "constraints" => new True(array("message" => "form.terms_and_conditions.error_message")),
                'label'      => 'form.terms_and_conditions',
                'label_attr' => array('class'=>'checkbox')
            )
        );

        $builder->add('city','text',  array('required' => false));

        $cityLocationTransformer = new CityLocationTransformer($options['cityLocationManager']);

//        $clientTransformer = new ClientTransformer($options['clientManager']);

        $builder->add(  $builder->create('city','hidden',   array('required' => false))
            ->addModelTransformer($cityLocationTransformer)
        );

        /*$builder->add(  $builder->create('client','text',  array('required' => false,
            'data' => $options['client']))
            ->addModelTransformer($clientTransformer)
        );*/

        $builder->add('ip',        'hidden',  array('required' => false,'data'  => $options['ip']));
        $builder->add('language', 'hidden',   array('required' => false,'data'  => $options['language']));

        $builder->add('recaptcha', 'ewz_recaptcha', array('mapped' => false, 'constraints' => array(new CaptchaTrue())));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver){
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\User',
            'intention'  => 'registration',
            'language' => null,
            //'client' => null,
            'ip'       => null,
        ));
        $resolver->setRequired(array(
            'cityLocationManager','language','ip'
        ));

        $resolver->setAllowedTypes(array(
            'cityLocationManager' => 'Ant\Bundle\ChateaClientBundle\Manager\CityManager'
        ));
    }

    public function getName()
    {
        return 'user_registration';
    }
}