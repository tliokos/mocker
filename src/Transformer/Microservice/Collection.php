<?php

namespace Mocker\Transformer\Microservice;

use League\Fractal\TransformerAbstract;

class Collection extends TransformerAbstract
{
    /**
     * @param array $microservice
     * @return array
     */
    public function transform(array $microservice) : array
    {
        return [
            'id' => $microservice['id'],
            'name' => $microservice['name'],
            'description' => $microservice['description'],
            'contracts' => $microservice['contracts']
        ];
    }
}