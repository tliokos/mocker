<?php

namespace Mocker\Transformer;

use Exception;
use League\Fractal\TransformerAbstract;

class Factory
{
    /**
     * @var NamespaceBuilder
     */
    private $namespaceBuilder;

    /**
     * Factory constructor.
     * @param NamespaceBuilder $namespaceBuilder
     */
    public function __construct(NamespaceBuilder $namespaceBuilder)
    {
        $this->namespaceBuilder = $namespaceBuilder;
    }

    /**
     * @param string $transformer
     * @return TransformerAbstract
     * @throws Exception
     */
    public function getInstance(string $transformer): TransformerAbstract
    {
        $namespace = $this->namespaceBuilder->getNamespace($transformer);
        if(!is_subclass_of($namespace, TransformerAbstract::class)) {
            throw new Exception("Transformer {$transformer} is invalid");
        }

        return new $namespace;
    }
}