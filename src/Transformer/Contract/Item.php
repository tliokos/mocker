<?php

namespace Mocker\Transformer\Contract;

use League\Fractal\TransformerAbstract;

class Item extends TransformerAbstract
{
    /**
     * @param array $contract
     * @return array
     */
    public function transform(array $contract) : array
    {
        return [
            'id' => $contract['id'],
            'microservice' => json_decode($contract['microservice'], true),
            'method' => $contract['method'],
            'url' => $contract['url'],
            'headers' => json_decode($contract['headers'], true),
            'request' => $contract['request'],
            'response' => $contract['response'],
            'code' => $contract['code'],
        ];
    }
}