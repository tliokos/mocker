<?php

namespace Mocker\Exception;

use Exception;

class ValidationException extends \Exception
{
    /**
     * ValidationException constructor.
     * @param array|null $errors
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct(array $errors = null, $code = 0, Exception $previous = null)
    {
         parent::__construct(json_encode($errors), $code, $previous);
    }

    /**
     * @return array
     */
    public function getDecodedMessage() : array
    {
        return json_decode($this->getMessage(), true);
    }
}