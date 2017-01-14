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
        $request = $contract['decoded'] ? $this->toArray($contract['request']) :  $contract['request'];
        $response = $contract['decoded'] ? $this->toArray($contract['response']) :  $contract['response'];
        return [
            'id' => $contract['id'],
            'microservice' => json_decode($contract['microservice'], true),
            'method' => $contract['method'],
            'url' => $contract['url'],
            'headers' => json_decode($contract['headers'], true),
            'request' => $request,
            'response' => $response,
            'code' => $contract['code'],
        ];
    }

    /**
     * @param string $input
     * @return array|null
     */
    private function toArray(string $input)
    {
        return json_decode(preg_replace('/\s+/', '', $input), true);
    }
}