<?php

namespace Mocker\Transformer\Contract;

use League\Fractal\TransformerAbstract;

class Id extends TransformerAbstract
{
    /**
     * @param array $contract
     * @return array
     */
    public function transform(array $contract) : array
    {
        return [
            'id' => $contract['id']
        ];
    }
}