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
        $request = $string = preg_replace('/\s+/', '', $contract['request']);
        $response = $string = preg_replace('/\s+/', '', $contract['response']);
        return [
            'id' => $contract['id'],
            'microservice' => json_decode($contract['microservice'], true),
            'method' => $contract['method'],
            'url' => $contract['url'],
            'headers' => json_decode($contract['headers'], true),
            'request' => json_decode($request, true),
            'response' => json_decode($response, true),
            'code' => $contract['code'],
        ];
    }
}