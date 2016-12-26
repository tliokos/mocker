<?php

namespace Mocker\Service;

use Mocker\{
    Exception\ValidationException,
    Storage\Contract as ContractStorage,
    Validator\AbstractValidator
};

class Contract
{
    /**
     * @var ContractStorage
     */
    private $contractStorage;

    /**
     * @var AbstractValidator
     */
    private $validator;

    /**
     * Contract constructor.
     * @param ContractStorage $contractStorage
     * @param AbstractValidator $validator
     */
    public function __construct(ContractStorage $contractStorage, AbstractValidator $validator)
    {
        $this->contractStorage = $contractStorage;
        $this->validator = $validator;
    }

    /**
     * @return array
     */
    public function list() : array
    {
        return $this->contractStorage->list();
    }

    /**
     * @param string $contractId
     * @return array
     */
    public function get(string $contractId) : array
    {
        return $this->contractStorage->get($contractId);
    }

    /**
     * @param array $data
     * @return string
     * @throws ValidationException
     */
    public function create(array $data) : string
    {
        if(!$this->validator->validate($data, 'create')) {
            throw new ValidationException($this->validator->getErrorMessages());
        }

        return $this->contractStorage->create($data);
    }

    /**
     * @param string $contractId
     * @param array $data
     * @return string
     * @throws ValidationException
     */
    public function update(string $contractId, array $data) : string
    {
        if(!$this->validator->validate($data, 'update')) {
            throw new ValidationException($this->validator->getErrorMessages());
        }

        return $this->contractStorage->update($contractId, $data);
    }

    /**
     * @param string $contractId
     * @return int
     */
    public function delete(string $contractId) : int
    {
        return $this->contractStorage->delete($contractId);
    }
}