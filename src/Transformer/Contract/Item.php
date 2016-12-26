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
            'id' => $contract['id']
        ];
    }
}