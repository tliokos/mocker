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
     * @param Redis $storage
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
        $key = $this->storage->keys($constraint->getHash())[0];
        if($key) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}