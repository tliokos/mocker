<?php

namespace Mocker\Constraint;

use Symfony\Component\Validator\Constraint;

class Json extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The value is not valid.';

    /**
     * @return string
     */
    public function validatedBy() : string
    {
        return 'json.validator';
    }
}