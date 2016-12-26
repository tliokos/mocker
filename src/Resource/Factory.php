<?php

namespace Mocker\Resource;

use League\Fractal\{
    Resource\Item,
    Resource\Collection,
    Resource\ResourceInterface,
    TransformerAbstract
};

class Factory
{
    const ITEM = 'Item';

    const COLLECTION = 'Collection';

    /**
     * @param string $type
     * @param array $data
     * @param TransformerAbstract $transformer
     * @return ResourceInterface
     * @throws \Exception
     */
    public function getInstance(string $type, array $data, TransformerAbstract $transformer) : ResourceInterface
    {
        switch($type) {
            case self::ITEM;
                $resource = new Item($data, $transformer);
            break;
            case self::COLLECTION;
                $resource = new Collection($data, $transformer);
            break;
            default:
                throw new \Exception("Resource type {$type} is not defined");
        }

        return $resource;
    }
}