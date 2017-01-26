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
                ['Provide your class name', [], false],
                ['Enter your property name such as isFinished, orderNumber', [], false]
            )
            ->willReturnOnConsecutiveCalls(
                'ModelObject',
                'order',
                'Order',
                'orderNumber'
            );
        $this->command->method('choice')
            ->withConsecutive(
                [
                    'provide type for this property: number, string, etc. Default is string.',
                    ValidationFactory::getValidationOptions(),
                    false,
                ],
                [
                    'provide type for this property: number, string, etc. Default is string.',
                    ValidationFactory::getValidationOptions(),
                    false,
                ]
            )
            ->willReturnOnConsecutiveCalls('class', 'string');
        $processor->request($this->command);
    }
}
