<?php

namespace Mocker\Storage;

use Redis;

class Contract
{
    const CONTRACTS_KEY = 'contract:%s';

    /**
     * @var Redis
     */
    private $storage;

    /**
     * Contract constructor.
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
        $contracts = [];
        $keys = $this->storage->keys(sprintf(self::CONTRACTS_KEY, '*'));
        foreach($keys as $key) {
            $contracts[] = $this->storage->hGetAll($key);
        }

        return $contracts;
    }

    /**
     * @param string $contractId
     * @return array
     */
    public function get(string $contractId) : array
    {
        return $this->storage->hGetAll(sprintf(self::CONTRACTS_KEY, $contractId));
    }

    /**
     * @param $contract
     * @return string
     */
    public function create($contract) : string
    {
        $contractId = md5($contract['microservice']['name'] . $contract['method'] . $contract['url']);
        $contract = array_merge(['id' => $contractId], $contract);
        $this->storage->hMset(sprintf(self::CONTRACTS_KEY, $contractId), array_map(function($field) {
            return is_array($field) ? json_encode($field) : $field;
        }, $contract));

        return $contractId;
    }

    /**
     * @param string $contractId
     * @param array $data
     * @return string
     */
    public function update(string $contractId, array $data) : string
    {
        $contract = array_merge(['id' => $contractId], $data);
        return $this->storage->hMset(sprintf(self::CONTRACTS_KEY, $contractId), array_map(function($field) {
            return is_array($field) ? json_encode($field) : $field;
        }, $contract));
    }

    /**
     * @param string $contractId
     * @return int
     */
    public function delete(string $contractId) : int
    {
        return $this->storage->del(sprintf(self::CONTRACTS_KEY, $contractId));
    }
}