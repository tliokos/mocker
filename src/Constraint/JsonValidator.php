<?php

namespace Mocker\Constraint;

use Symfony\Component\Validator\{ConstraintValidator, Constraint};

class JsonValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $value = preg_replace('/\s+/', '', $value);
        if($value && !json_decode($value, true)) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}