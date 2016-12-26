<?php

namespace Mocker\Test\Unit\Service\Transformer;

use Mocker\Transformer\Factory;
use Mocker\Transformer\NamespaceBuilder;

class FactoryTest extends \Codeception\Test\Unit
{
    /**
     * @expectedException \Exception
     */
    public function testTransformerFactoryCannotCreateNonExistingTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        (new Factory($namespaceBuilder))->getInstance('non.existing.transformer');
    }
}