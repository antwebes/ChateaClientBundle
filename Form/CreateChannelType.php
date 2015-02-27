<?php
namespace Ant\Bundle\ChateaClientBundle\Form;

use Ant\Bundle\ChateaClientBundle\Form\Transformer\ChannelTypeToNameTransformer;
use Ant\Bundle\ChateaClientBundle\Manager\ChannelTypeManager;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CreateChannelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $channelTypes = $this->buildChannelsTypeChoices($options['channelTypeManager']);
        $builder
            ->add('name', 'text')
            ->add('irc_channel', 'text')
            ->add('description', 'textarea', array('required' => false))
            ->add('language', 'choice',
                array('choices'=> array('en'=>'form.language.english','es'=>'form.language.english.spanish')));


        $builder->add(
            $builder->create('channel_type', 'choice',
                array('choices'=>$channelTypes)
            )->addModelTransformer(new ChannelTypeToNameTransformer($options['channelTypeManager']))
        );

        /*$builder->add('recaptcha', 'ewz_recaptcha', array('mapped' => false, 'constraints' => array(new CaptchaTrue())));*/
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\Channel'
        ));
        $resolver->setRequired(array('channelTypeManager'));
    }

    public function getName()
    {
        return 'iframe_channel';
    }

    private function buildChannelsTypeChoices(ChannelTypeManager $channelTypeManager)
    {
        $channelTypesCollection = $channelTypeManager->findAll();

        $choices = array();
        foreach($channelTypesCollection as $channelType) {
            $choices[$channelType->getName()] = 'form.channelType.'.$channelType->getName();
        }

        return $choices;
    }
}