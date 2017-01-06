<?php

namespace Mocker\Validator;

use Mocker\{
    Constraint\Json,
    Constraint\UniqueStorageKey,
    Storage\Contract as ContractStorage
};
use Symfony\Component\Validator\Constraints as Assert;

class Contract extends AbstractValidator
{
    /**
     * @return Assert\Collection
     */
    protected function create() : Assert\Collection
    {
        $contract = $this->getResource();
        return new Assert\Collection([
            'microservice' => new Assert\Collection([
                'id' => [new Assert\NotBlank()],
                'name' => [new Assert\NotBlank()]
            ]),
            'method' => [new Assert\NotBlank()],
            'url' => [
                new Assert\NotBlank(),
                new UniqueStorageKey(
                    sprintf(
                        ContractStorage::CONTRACTS_KEY,
                        ContractStorage::getId($contract['microservice']['name'], $contract['method'], $contract['url']))
                ),
            ],
            'headers' => [],
            'request' => [new Json()],
            'code' => [new Assert\NotBlank()],
            'response' => [new Json()],
        ]);
    }

    /**
     * @return Assert\Collection
     */
    protected function update() : Assert\Collection
    {
        return new Assert\Collection([
            'microservice' => new Assert\Collection([
                'id' => [new Assert\NotBlank()],
                'name' => [new Assert\NotBlank()]
            ]),
            'method' => [new Assert\NotBlank()],
            'url' => [new Assert\NotBlank()],
            'headers' => [],
            'request' => [new Json()],
            'code' => [new Assert\NotBlank()],
            'response' => [new Json()],
        ]);
    }
}