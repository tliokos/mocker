<?php

namespace Mocker\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Mocker\{
    Constraint\UniqueStorageKey,
    Storage\Microservice as MicroserviceStorage
};

class Microservice extends AbstractValidator
{
    /**
     * @return Assert\Collection
     */
    protected function create() : Assert\Collection
    {
        return new Assert\Collection([
            'name' => [
                new Assert\NotBlank(),
                new UniqueStorageKey(
                    MicroserviceStorage::MICROSERVICES_KEY,
                    function($name){
                        return md5($name);
                    }),
                new Assert\Regex([
                    'pattern' => '/^[a-zA-Z\-\_]+$/',
                    'message' => 'The value should contain only letters, dashes and/or underscore',
                ])
            ],
            'description' => []
        ]);
    }
}