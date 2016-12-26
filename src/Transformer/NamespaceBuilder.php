<?php

namespace Mocker\Transformer;

class NamespaceBuilder
{
    /**
     * @param string $transformer
     * @return string
     */
    public function getNamespace(string $transformer) : string
    {
        $namespaceSuffix = array_map(function($namespaceElement){
            return '\\' . ucfirst($namespaceElement);
        }, explode('.', $transformer));

        return __NAMESPACE__ . implode($namespaceSuffix);
    }
}