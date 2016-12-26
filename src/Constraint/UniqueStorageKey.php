<?php

namespace Mocker\Constraint;

use Closure;
use Symfony\Component\Validator\Constraint;

class UniqueStorageKey extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The value is already used.';

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var callable
     */
    private $hashingAlgorithm;

    /**
     * UniqueStorageKey constructor.
     * @param string $pattern
     * @param callable $hashingAlgorithm
     */
    public function __construct(string $pattern, Closure $hashingAlgorithm)
    {
        parent::__construct();
        $this->pattern = $pattern;
        $this->hashingAlgorithm = $hashingAlgorithm;
    }

    /**
     * @return string
     */
    public function getPattern() : string
    {
        return $this->pattern;
    }

    /**
     * @return callable
     */
    public function getHashingAlgorithm() : callable
    {
        return $this->hashingAlgorithm;
    }

    /**
     * @return string
     */
    public function validatedBy() : string
    {
        return 'unique.storage.key.validator';
    }
}