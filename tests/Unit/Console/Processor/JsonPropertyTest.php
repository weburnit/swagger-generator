<?php
namespace Tests\Weburnit\Unit\Console\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\JsonPropertyProcessor;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

class JsonPropertyTest extends \PHPUnit_Framework_TestCase
{
    private $command;

    protected function setUp()
    {
        parent::setUp();
        $this->command = $this->createMock(Command::class);
        $this->command->method('ask')->withAnyParameters()->willReturn('Description');
        $this->command->method('askWithCompletion')->withAnyParameters()->willReturn('Y');
    }

    /**
     * @param $key
     * @param $property
     * @param $value
     *
     * @dataProvider getPrimitiveDataProvider()
     */
    public function testPrimitiveProperty($key, $property, $value)
    {
        $processor = new JsonPropertyProcessor($key, $value);
        $this->command->method('anticipate')
            ->withConsecutive(
                [
                    sprintf('Rename field(%s) or Press Enter to ignore', $key),
                    [],
                    $key,
                ],
                [
                    sprintf('provide type for this property: number, string, etc. Current is "%s"', $property),
                    [],
                    $property,
                ]
            )
            ->willReturnOnConsecutiveCalls(
                $key,
                $property
            );
        $processor->request($this->command);
    }

    /**
     * @dataProvider getExtendArrayDataProvider
     *
     * @param $key
     * @param $property
     * @param $value
     */
    public function testArrayDataProperty($key, $property, $value)
    {
        $processor = new JsonPropertyProcessor($key, $value);
        $this->command->method('anticipate')
            ->withConsecutive(
                [
                    sprintf('Rename field(%s) or Press Enter to ignore', $key),
                    [],
                    $key,
                ],
                [
                    sprintf('Provide your class name for field(%s)', $key),
                    [],
                    false,
                ]
            )
            ->willReturnOnConsecutiveCalls(
                $key,
                $property
            );
        $processor->request($this->command);
    }

    public function getPrimitiveDataProvider()
    {
        return [
            '#string'  => [
                'key'      => 'orderNumber',
                'property' => 'string',
                'value'    => 'ORDER-Number',
            ],
            '#integer' => [
                'key'      => 'price',
                'property' => ValidationFactory::TYPE_INTEGER,
                'value'    => 2000,
            ],
            '#numeric' => [
                'key'      => 'price',
                'property' => ValidationFactory::TYPE_NUMERIC,
                'value'    => 7024.12342,
            ],
            '#boolean' => [
                'key'      => 'price',
                'property' => ValidationFactory::TYPE_BOOLEAN,
                'value'    => true,
            ],
            '#email'   => [
                'key'      => 'price',
                'property' => ValidationFactory::TYPE_EMAIL,
                'value'    => 'aan@saloniz.com',
            ],
        ];
    }

    public function getExtendArrayDataProvider()
    {
        return [
            '#1' => [
                'key'      => 'itemList',
                'property' => 'array',
                'value'    => [['orderNumber' => 'something']],
            ],
        ];
    }
}