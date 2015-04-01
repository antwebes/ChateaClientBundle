<?php

namespace Ant\Bundle\ChateaClientBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * Class Language
 * @package Chatea\UtilBundle\Validator\Constraints
 */
class Language extends Constraint
{
    public $message = 'This value %string% is not a valid language.';
    /**
     * Returns the name of the class that validates this constraint
     *
     * By default, this is the fully qualified name of the constraint class
     * suffixed with "Validator". You can override this method to change that
     * behaviour.
     *
     * @return string
     *
     * @api
     */
    public function validatedBy()
    {
        return 'chatea_language_validator';
    }
}
