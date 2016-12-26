<?php

namespace Mocker\Resource;

interface FormatterInterface
{
    /**
     * @param string $transformer
     * @return mixed
     */
    public function setTransformer(string $transformer)  : Formatter;

    /**
     * @param array $resource
     * @return array
     */
    public function formatItem(array $resource) : array;

    /**
     * @param array $resource
     * @return array
     */
    public function formatCollection(array $resource) : array;
}