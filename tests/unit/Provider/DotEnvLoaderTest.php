<?php

namespace Mocker\Test\Unit\Service\Provider;

class DotEnvLoaderTest extends \Codeception\Test\Unit
{
    public function testDotEnvLoadsTestingEnvironmentFile()
    {
        $this->assertEquals('testing', getenv('ENVIRONMENT'));
    }
}