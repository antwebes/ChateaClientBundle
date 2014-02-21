<?php
/**
 * Created by Javier Fernández Rodríguez for Ant-Webs S.L.
 * User: Xabier <jbier@gmail.com>
 * Date: 19/02/14 - 16:35
 */

namespace Ant\Bundle\ChateaClientBundle\    Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NickIrcValidator extends ConstraintValidator
{
    const LIMITS_NICK = 31;
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed      $value      The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if(!$this->isValidNick($value)){
            $this->context->addViolation(
                $constraint->message,
                array('%nickValue%' => $value)
            );
        }
    }

    private function isValidNick($nick)
    {
        if(empty($nick) || strlen($nick) > self::LIMITS_NICK ){
                return false;
        }
        $chr = str_split($nick);

        for($i = 0; $i < count($chr); $i++ ){
            if($chr[$i] >= 'A' && $chr[$i] <= '}'){
                /* "A"-"}" can occur anywhere in a nickname */
                continue;
            }
            if (((($chr[$i] >= '0') && ($chr[$i] <= '9')) || ($chr[$i] == '-')) && $i != 0 ){
                /* "0"-"9", "-" can occur anywhere BUT the first char of a nickname */
                continue;
            }
            /* invalid character! abort */
            return false;
        }
        return true;
    }
} 