<?php

namespace Mocker\Service;

use Mocker\{
    Exception\ValidationException,
    Storage\Microservice as MicroserviceStorage,
    Validator\AbstractValidator
};

class Microservice
{
    /**
     * @var MicroserviceStorage
     */
    private $microserviceStorage;

    /**
     * @var AbstractValidator
     */
    private $validator;

    /**
     * Microservice constructor.
     * @param MicroserviceStorage $microserviceStorage
     * @param AbstractValidator $validator
     */
    public function __construct(
        MicroserviceStorage $microserviceStorage,
        AbstractValidator $validator
    )
    {
        $this->microserviceStorage = $microserviceStorage;
        $this->validator = $validator;
    }

    /**
     * @return array
     */
    public function list() : array
    {
        return $this->microserviceStorage->list();
    }

    /**
     * @param string $microserviceId
     * @return array
     */
    public function get(string $microserviceId) : array
    {
        return $this->microserviceStorage->get($microserviceId);
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

        return $this->microserviceStorage->create($data);
    }

    /**
     * @param string $microserviceId
     * @return int
     */
    public function delete(string $microserviceId) : int
    {
        return $this->microserviceStorage->delete($microserviceId);
    }

    /**
     * @param string $microserviceId
     * @param int $number
     * @return int
     */
    public function updateContractsCounter(string $microserviceId, int $number) : int
    {
        return $this->microserviceStorage->updateContractsCounter($microserviceId, $number);
    }

    /**
     * @param string $microserviceId
     * @return string
     */
    public function getResourceUri(string $microserviceId) : string
    {
        return sprintf('/mocker-api/microservices/%s', $microserviceId);
    }
}