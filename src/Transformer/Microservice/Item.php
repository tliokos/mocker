<?php

namespace Mocker\Transformer\Microservice;

use League\Fractal\TransformerAbstract;

class Item extends TransformerAbstract
{
    /**
     * @param array $microservice
     * @return array
     */
    public function transform(array $microservice) : array
    {
        return [
            'id' => $microservice['id']
        ];
    }
}