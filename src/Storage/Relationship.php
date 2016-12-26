<?php

namespace Mocker\Storage;

use Redis;

class Relationship
{
    const RELATIONSHIPS_KEY = 'relationship:%s';

    /**
     * @var Redis
     */
    private $storage;

    /**
     * Relationship constructor.
     * @param Redis $storage
     */
    public function __construct(Redis $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $microserviceId
     * @return array
     */
    public function getContracts(string $microserviceId) : array
    {
        return $this->storage->sMembers(sprintf(self::RELATIONSHIPS_KEY, $microserviceId));
    }

    /**
     * @param string $microserviceId
     * @param string $relationshipId
     * @return int
     */
    public function addContract(string $microserviceId, string $relationshipId) : int
    {
        return $this->storage->sAdd(sprintf(self::RELATIONSHIPS_KEY, $microserviceId), $relationshipId);
    }

    /**
     * @param string $microserviceId
     * @param string $contractId
     * @return int
     */
    public function removeContract(string $microserviceId, string $contractId) : int
    {
        return $this->storage->sRem(sprintf(self::RELATIONSHIPS_KEY, $microserviceId), $contractId);
    }
}