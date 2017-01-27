<?php
namespace Tests\Weburnit\Unit\Console\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\JsonModelProcessor;

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

        $this->command->method('anticipate')
            ->withConsecutive(
                [
                    'What is your parent Model?',
                    [],
                    false,
                ]
            )
            ->willReturnOnConsecutiveCalls(
                'JsonClass'
            );

        $json = '{"platformName":"LAZADA_MY","price": 242.000,"created_at": "2017-12-12 12:12:12"}';


        $processor = new JsonModelProcessor($json);

        $processor->request($this->command);
    }
}
