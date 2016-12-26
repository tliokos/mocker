<?php

namespace Mocker\Constraint;

use Redis;
use Symfony\Component\Validator\{ConstraintValidator, Constraint};

class UniqueStorageKeyValidator extends ConstraintValidator
{
    /**
     * @var Redis
     */
    private $storage;

    /**
     * UniqueStorageKeyValidator constructor.
     * @param Redis $queryBuilder
     */
    public function __construct(Redis $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $microserviceId = ($constraint->getHashingAlgorithm())($value);
        $key = $this->storage->keys(sprintf($constraint->getPattern(), $microserviceId))[0];
        if($key) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}