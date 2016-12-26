<?php

namespace Mocker\Validator;

use Symfony\Component\Validator\Validator\RecursiveValidator;

abstract class AbstractValidator
{
    /**
     * @var RecursiveValidator
     */
    protected $validator;

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * AbstractValidator constructor.
     * @param RecursiveValidator $validator
     */
    public function __construct(RecursiveValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @param array $resource
     * @param $constraints
     * @return bool
     */
    public function validate(array $resource, string $context) : bool
    {
        $constraints = $this->getConstraints($context);
        $validationErrors = $this->validator->validate($resource, $constraints);

        if(count($validationErrors)) {
            foreach ($validationErrors as $validationError) {
                $fieldName = ucfirst(str_replace(['[', ']'], '', $validationError->getPropertyPath()));
                $this->errorMessages[$fieldName] = $validationError->getMessage();
            }

            return false;
        }

        return true;
    }

    /**
     * @param string $context
     * @return mixed
     * @throws \Exception
     */
    private function getConstraints(string $context)
    {
        if(!is_callable([$this, $context])) {
            throw new \Exception(sprintf('%s::%s() method is not implemented', static::class, $context));
        }

        return $this->$context();
    }
}