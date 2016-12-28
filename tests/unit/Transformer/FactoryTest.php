<?php

namespace Mocker\Test\Unit\Transformer;

use Mocker\Transformer\{Factory, NamespaceBuilder};

class FactoryTest extends \Codeception\Test\Unit
{
    public function testTransformerFactoryCanCreateContractItemTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        $transformer = (new Factory($namespaceBuilder))->getInstance('contract.item');
        $this->assertInstanceOf('Mocker\Transformer\Contract\Item', $transformer);
    }

    public function testTransformerFactoryCanCreateContractCollectionTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        $transformer = (new Factory($namespaceBuilder))->getInstance('contract.collection');
        $this->assertInstanceOf('Mocker\Transformer\Contract\Collection', $transformer);
    }

    public function testTransformerFactoryCanCreateContractIdTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        $transformer = (new Factory($namespaceBuilder))->getInstance('contract.id');
        $this->assertInstanceOf('Mocker\Transformer\Contract\Id', $transformer);
    }

    public function testTransformerFactoryCanCreateMicroserviceItemTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        $transformer = (new Factory($namespaceBuilder))->getInstance('microservice.item');
        $this->assertInstanceOf('Mocker\Transformer\Microservice\Item', $transformer);
    }

    public function testTransformerFactoryCanCreateMicroserviceCollectionTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        $transformer = (new Factory($namespaceBuilder))->getInstance('microservice.collection');
        $this->assertInstanceOf('Mocker\Transformer\Microservice\Collection', $transformer);
    }

    /**
     * @expectedException \Exception
     */
    public function testTransformerFactoryCannotCreateNonExistingTransformer()
    {
        $namespaceBuilder = new NamespaceBuilder();
        (new Factory($namespaceBuilder))->getInstance('non.existing.transformer');
    }
}