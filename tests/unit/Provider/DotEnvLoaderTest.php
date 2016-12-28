<?php

namespace Mocker\Test\Unit\Provider;

class DotEnvLoaderTest extends \Codeception\Test\Unit
{
    public function testDotEnvLoadsTestingEnvironmentFile()
    {
        $this->assertEquals('testing', getenv('ENVIRONMENT'));
    }
}