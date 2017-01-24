<?php
namespace Tests\Unit\Console\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\ModelProcessor;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

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
        $processor = new ModelProcessor();
        $this->command->method('anticipate')
            ->withConsecutive(
                [
                    'Provide your model Model Class',
                    [],
                    false,
                ],
                [sprintf('%d. Enter your property name such as isFinished, orderNumber', 1), [], false],
                [sprintf('%d. Enter your property name such as isFinished, orderNumber', 2), [], false],
                ['Exp: 10,20', [], false]
            )
            ->willReturnOnConsecutiveCalls('ModelObject', 'orderNumber', 'sourceType', '10,20');
        $this->command->method('choice')
            ->withConsecutive(
                [
                    'provide type for this property: number, string...',
                    ValidationFactory::getValidationOptions(),
                    false,
                ],
                [
                    'provide type for this property: number, string...',
                    ValidationFactory::getValidationOptions(),
                    false,
                ]
            )
            ->willReturnOnConsecutiveCalls('numeric', 'between');
        $result = $processor->request($this->command);
    }
}
