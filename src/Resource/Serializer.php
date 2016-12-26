<?php

namespace Mocker\Resource;

use League\Fractal\Serializer\ArraySerializer;

class Serializer extends ArraySerializer
{
    /**
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function collection($resourceKey, array $data) : array
    {
        return ['data' => $data];
    }

    /**
     * @param string $resourceKey
     * @param array $data
     * @return array
     */
    public function item($resourceKey, array $data) : array
    {
        return ['data' => $data];
    }

    /**
     * @return array
     */
    public function null() : array
    {
        return ['data' => []];
    }
}
