<?php
/*
 * Created by Javier Fernández Rodríguez for Ant-Webs S.L.
 * User: Xabier <jbier@gmail.com>
 * Date: 31/03/15 - 12:47
 */

namespace Ant\Bundle\ChateaClientBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Ant\Bundle\ChateaClientBundle\Util\YamlFileLoader;
/**
 * Class languagesValidator
 * @package Chatea\UtilBundle\Validator\Constraints
 */
class LanguageValidator extends ConstraintValidator
{
    /**
     * @var YamlFileLoader
     */
    private $loader;

    public function __construct(YamlFileLoader $loader)
    {
        $this->loader = $loader;
    }


    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value || '' === $value) {
            return;
        }

        $value = (string) $value;
        $languages = $this->getLanguageNames();

        if (!array_key_exists($value,$languages)) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }

    private function getLanguageNames()
    {
        return $this->loader->getParameters();
    }
}