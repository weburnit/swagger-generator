<?php
namespace Tests\Unit\Console\Parser;

use Weburnit\Console\Commands\Processor\ModelProcessor;
use Weburnit\Console\Commands\Processor\ProcessorResult;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

abstract class AbstractParser extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ModelProcessor
     */
    protected $modelProcessor;

    protected function setUp()
    {
        parent::setUp();
        $modelProcessor = $this->createMock(ModelProcessor::class);
        $modelProcessor->method('getModelClass')->willReturn('Product');
        $modelProcessor->method('getDescription')->willReturn('Product Description');
        $properties = [];
        $first      = new ProcessorResult('orderNumber', new ProcessorResult(ValidationFactory::TYPE_STRING));
        $first->setDescription('Order Number');
        $first->setRequired(true);

        $second = new ProcessorResult(
            'platformCode',
            new ProcessorResult(
                ValidationFactory::EXTENDED_TYPE_EXISTS,
                new ProcessorResult('product,platformCode', new ProcessorResult(ValidationFactory::TYPE_STRING))
            )
        );
        $second->setDescription('platform code');
        $second->setRequired(true);
        $properties[] = $first;
        $properties[] = $second;
        $modelProcessor->method('getProperties')->willReturn($properties);
        $this->modelProcessor = $modelProcessor;
    }
}