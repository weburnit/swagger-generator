<?php
namespace Tests\Weburnit\Unit\Console\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\JsonModelProcessor;
use Weburnit\Console\Commands\Processor\ProcessorResult;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

class JsonModelProcessorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $command;

    public function testParse()
    {
        $this->command = $this->createMock(Command::class);
        $this->command->method('ask')->withAnyParameters()->willReturn('Description');
        $this->command->method('askWithCompletion')->withAnyParameters()->willReturn('Y');
        $this->command->method('info')->withAnyParameters();

        $this->command->method('anticipate')
            ->withConsecutive(
                [
                    'What is your parent Model?',
                    [],
                    false,
                ],
                ['Rename field(platformName) or Press Enter to ignore', ['platformName'], 'platformName'],
                [
                    'provide type for this property: number, string, etc. Current is "string"',
                    ValidationFactory::getValidationOptions(),
                    'string',
                ]
            )
            ->willReturnOnConsecutiveCalls(
                'JsonClass',
                'platformName',
                'string'
            );

        $json = '{"platformName":"LAZADA_MY"}';

        $processor = new JsonModelProcessor(json_decode($json, true));

        $processor->request($this->command);
        static::assertEquals('JsonClass', $processor->getModelClass());
        static::assertEquals('JsonClass', $processor->getInput());
        static::assertEquals($processor, $processor->getValue());
        static::assertTrue($processor->isRequired());
        $processor->setNamespace('ProjectNamespace');
        static::assertEquals('ProjectNamespace', $processor->getNamespace());
        static::assertEquals('Description', $processor->getDescription());
        static::assertEquals(1, count($processor->getProperties()), 'Must have 1 property');

        /**
         * @var $property ProcessorResult
         */
        $property = $processor->getProperties()[0];
        static::assertInstanceOf(
            ProcessorResult::class,
            $property,
            'Must have one property'
        );
        static::assertEquals('platformName', $property->getInput(), 'Must have platform name as property');
        static::assertEquals('string', $property->getValue()->getInput(), 'Must be string');
    }
}
