<?php

namespace Mocker\Service;

use Mocker\Storage\Relationship as RelationshipStorage;

class Relationship
{
    /**
     * @var RelationshipStorage
     */
    private $relationshipStorage;

    /**
     * Relationship constructor.
     * @param RelationshipStorage $relationshipStorage
     */
    public function __construct(RelationshipStorage $relationshipStorage)
    {
        $this->relationshipStorage = $relationshipStorage;
    }

    /**
     * @param $microserviceId
     * @return array
     */
    public function getContracts($microserviceId) : array
    {
        return $this->relationshipStorage->getContracts($microserviceId);
    }

    /**
     * @param string $microserviceId
     * @param string $contractId
     * @return int
     */
    public function addContract(string $microserviceId, string $contractId) : int
    {
        return $this->relationshipStorage->addContract($microserviceId, $contractId);
    }

    /**
     * @param string $microserviceId
     * @param string $contractId
     * @return int
     */
    public function removeContract(string $microserviceId, string $contractId) : int
    {
        return $this->relationshipStorage->removeContract($microserviceId, $contractId);
    }
}