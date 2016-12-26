<?php

namespace Mocker\Test\Unit\Serice\Transformer;

use Mocker\Transformer\NamespaceBuilder;

class NamespaceBuilderTest extends \Codeception\Test\Unit
{
    public function testNamespaceBuilderReturnsCorrectNamespace()
    {
        $namespaceBuilder = new NamespaceBuilder();
        $this->assertEquals($namespaceBuilder->getNamespace('test.collection'), 'Mocker\Transformer\Test\Collection');
    }
}