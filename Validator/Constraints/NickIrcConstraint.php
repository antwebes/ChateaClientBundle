<?php
/**
 * Created by Javier Fernández Rodríguez for Ant-Webs S.L.
 * User: Xabier <jbier@gmail.com>
 * Date: 19/02/14 - 15:18
 */

namespace Ant\Bundle\ChateaClientBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class NickIrcConstraint
 * @package Ant\Bundle\ChateaClientBundle\Validator\Constraints
 * @Annotation
 */
class NickIrcConstraint extends Constraint
{
    public $message = 'The username is not valid. The username is not include spaces, it is not start with number, or it is not include the special characters';

    public function validatedBy()
    {
        return get_class(new NickIrcValidator());
    }
} 