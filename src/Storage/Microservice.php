<?php

namespace Mocker\Storage;

use Redis;

class Microservice
{
    const MICROSERVICES_KEY = 'microservice:%s';

    const CONTRACTS_FIELD = 'contracts';

    /**
     * @var Redis
     */
    private $storage;

    /**
     * Microservice constructor.
     * @param Redis $storage
     */
    public function __construct(Redis $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public function list() : array
    {
        $microservices = [];
        $keys = $this->storage->keys(sprintf(self::MICROSERVICES_KEY, '*'));
        foreach($keys as $key) {
            $microservices[] = $this->storage->hGetAll($key);
        }

        return $microservices;
    }

    /**
     * @param string $microserviceId
     * @return array
     */
    public function get(string $microserviceId) : array
    {
        return $this->storage->hGetAll(sprintf(self::MICROSERVICES_KEY, $microserviceId));
    }

    /**
     * @param array $microservice
     * @return string
     */
    public function create(array $microservice) : string
    {
        $microserviceId = md5($microservice['name']);
        $microservice = array_merge(['id' => $microserviceId, self::CONTRACTS_FIELD => 0], $microservice);
        $this->storage->hMset(sprintf(self::MICROSERVICES_KEY, $microserviceId), $microservice);

        return $microserviceId;
    }

    /**
     * @param $microserviceId
     * @return int
     */
    public function delete(string $microserviceId) : int
    {
        return $this->storage->del(sprintf(self::MICROSERVICES_KEY, $microserviceId));
    }

    /**
     * @param string $microserviceId
     * @param int $number
     * @return int
     */
    public function updateContractsCounter(string $microserviceId, int $number) : int
    {
        return $this->storage->hIncrBy(
            sprintf(self::MICROSERVICES_KEY, $microserviceId),
            self::CONTRACTS_FIELD,
            $number
        );
    }
}