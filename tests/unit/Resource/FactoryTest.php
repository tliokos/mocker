<?php

namespace Mocker\Test\Unit\Service\Resource;

use Mockery;
use Mocker\Resource\Factory;

class FactoryTest extends \Codeception\Test\Unit
{
    private $resourceFactory;

    protected function _before()
    {
        $this->resourceFactory = new Factory;
    }

    public function testResourceFactoryCanCreateResourceItem()
    {
        $transformer = Mockery::mock('\League\Fractal\TransformerAbstract');
        $resource = $this->resourceFactory->getInstance(Factory::ITEM, [], $transformer);
        $this->assertInstanceOf('\League\Fractal\Resource\Item', $resource);
    }

    public function testResourceFactoryCanCreateResourceCollection()
    {
        $transformer = Mockery::mock('\League\Fractal\TransformerAbstract');
        $resource = $this->resourceFactory->getInstance(Factory::COLLECTION, [], $transformer);
        $this->assertInstanceOf('\League\Fractal\Resource\Collection', $resource);
    }

    /**
     * @expectedException \Exception
     */
    public function testResourceFactoryCannotCreateOtherTypeOfResource()
    {
        $transformer = Mockery::mock('\League\Fractal\TransformerAbstract');
        $resource = $this->resourceFactory->getInstance('List', [], $transformer);
        $this->assertInstanceOf('\League\Fractal\Resource\Collection', $resource);
    }
}