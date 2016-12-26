<?php

namespace Mocker\Test\Unit\Service\Validator;

use Mockery;
use Codeception\Util\Fixtures;
use Symfony\Component\Validator\Constraints as Assert;

class AbstractValidatorTest extends \Codeception\Test\Unit
{
    private $validator;

    public function _before()
    {
        parent::_before();
        $app = Fixtures::get('app');
        $this->validator = Mockery::mock('Mocker\Validator\AbstractValidator[create]', [
            $app['validator']
        ], function($mock) {
            $mock->shouldReceive('create')->andReturn(new Assert\Collection([
                'name' => new Assert\NotBlank(),
                'description' => new Assert\NotBlank()
            ]));
        })->makePartial();
    }

    public function _after()
    {
        Mockery::close();
    }

    public function testValidationPasses()
    {
        $validationResult = $this->validator->validate([
            'name' => 'There is a name',
            'description' => 'There is a description',
        ], 'create');

        $this->assertEquals(true, $validationResult);
        $this->assertArrayNotHasKey('Name', $this->validator->getErrorMessages());
        $this->assertArrayNotHasKey('Name', $this->validator->getErrorMessages());
    }

    public function testValidationFails()
    {
        $validationResult = $this->validator->validate([
            'name' => '',
            'description' => '',
        ], 'create');

        $this->assertEquals(false, $validationResult);
        $this->assertArrayHasKey('Name', $this->validator->getErrorMessages());
    }

    /**
     * @expectedException \Exception
     */
    public function testValidationThrowsException()
    {
        $this->validator->validate([
            'name' => 'Na',
            'description' => 'De',
        ], 'Non Existing Validation Context');
    }
}