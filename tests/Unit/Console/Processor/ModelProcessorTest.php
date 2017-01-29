<?php
namespace Tests\Weburnit\Unit\Console\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;
use Weburnit\Console\Commands\Processor\ValueObjectProcessor;

/**
 * Created by PhpStorm.
 * User: paulnguyen
 * Date: 1/18/17
 * Time: 5:03 PM
 */
class ModelProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $command;

    public function setUp()
    {
        $this->command = $this->createMock(Command::class);
        $this->command->method('ask')->withAnyParameters()->willReturn('Description');
        $this->command->method('askWithCompletion')->withAnyParameters()->willReturn('Y');
        $this->command->method('info')->withAnyParameters();
    }

    public function testHandle()
    {
        $processor = new ValueObjectProcessor();
        $this->command->method('anticipate')
            ->withConsecutive(
                [
                    'Provide your Model Class',
                    [],
                    false,
                ],
                ['Enter your property name such as isFinished, orderNumber', [], false],
                [
                    'provide type for this property: number, string, etc. Current is "string"',
                    ValidationFactory::getValidationOptions(),
                    'string',
                ],
                ['Provide your class name', [], false],
                ['Enter your property name such as isFinished, orderNumber', [], false],
                [
                    'provide type for this property: number, string, etc. Current is "string"',
                    ValidationFactory::getValidationOptions(),
                    'string',
                ]
            )
            ->willReturnOnConsecutiveCalls(
                'ModelObject',
                'order',
                'class',
                'Order',
                'orderNumber',
                'string'
            );
        $processor->request($this->command);
    }
}
