<?php


namespace Ant\Bundle\ChateaClientBundle\Form;

use Ant\Bundle\ChateaClientBundle\Api\Collection\ApiCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ChannelFiltersFromType extends AbstractType
{
    private $choices = array();
    function __construct(ApiCollection $channelTypes)
    {
        foreach ($channelTypes as $channelType)
        {
            $this->choices[$channelType->getName()] = $channelType->getName();
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('channelType', 'choice', array(
                'choices'   => $this->choices,
                'required'  => false,
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Ant\Bundle\ChateaClientBundle\Api\Model\ChannelFilter'
            ));
    }
    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return "filter";
    }
}