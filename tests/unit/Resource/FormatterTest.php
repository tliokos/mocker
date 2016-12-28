<?php

namespace Mocker\Test\Unit\Resource;

use Mockery;
use Codeception\Util\Fixtures;
use Mocker\Resource\Formatter;

class FormatterTest extends \Codeception\Test\Unit
{
    private $app;

    private $resourceFormatter;

    private $transformerInputData = [
        'key' => 'value'
    ];

    private $transformerReturnData = [
        'transformed_key' => 'transformed_value'
    ];

    protected function _before()
    {
        $this->app = Fixtures::get('app');
        $this->resourceFormatter = $this->getResourceFormatter();
    }

    protected function _after()
    {
        Mockery::close();
    }

    public function testFormatterFormatsItem()
    {
        $data = $this->resourceFormatter->setTransformer('test.item')->formatItem($this->transformerInputData);
        $this->assertEquals(['data' => $this->transformerReturnData], $data);
    }

    public function testFormatterFormatsCollection()
    {
        $data = $this->resourceFormatter->setTransformer('test.item')->formatCollection([
            $this->transformerInputData,
            $this->transformerInputData
        ]);

        $this->assertEquals(['data' => [
            $this->transformerReturnData,
            $this->transformerReturnData,
        ]], $data);
    }

    /**
     * @return Formatter
     */
    private function getResourceFormatter()
    {
        $transformer = Mockery::mock('League\Fractal\TransformerAbstract[transform]', function($mock) {
            $mock->shouldReceive('transform')->andReturn($this->transformerReturnData);
        });

        $transformerFactory = Mockery::mock('Mocker\Transformer\Factory', function($mock) use ($transformer) {
            $mock->shouldReceive('getInstance')->andReturn($transformer);
        });

        return new Formatter(
            $this->app['resource.manager'],
            $this->app['resource.factory'],
            $transformerFactory
        );
    }
}