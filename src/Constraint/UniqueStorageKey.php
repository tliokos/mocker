<?php

namespace Mocker\Constraint;

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
    private $hash;

    /**
     * UniqueStorageKey constructor.
     * @param string $hash
     */
    public function __construct(string $hash)
    {
        parent::__construct();
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash() : string
    {
        return $this->hash;
    }

    /**
     * @return string
     */
    public function validatedBy() : string
    {
        return 'unique.storage.key.validator';
    }
}