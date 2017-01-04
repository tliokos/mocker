<?php

namespace Mocker\Validator;

use Mocker\{
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
                        md5($contract['microservice']['name'] . $contract['method'] . $contract['url']))
                ),
            ],
            'headers' => [],
            'request' => [],
            'code' => [new Assert\NotBlank()],
            'response' => [],
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
            'request' => [],
            'code' => [new Assert\NotBlank()],
            'response' => [],
        ]);
    }
}