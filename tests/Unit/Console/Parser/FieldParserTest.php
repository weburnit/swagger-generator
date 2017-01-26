<?php
namespace Tests\Weburnit\Unit\Console\Parser;


use gossi\codegen\model\PhpClass;
use Weburnit\Console\Commands\Parser\FieldParser;

class FieldParserTest extends AbstractParser
{
    /**
     * @var FieldAbstractParser
     */
    private $parser;

    protected function setUp()
    {
        parent::setUp();
        $this->parser = new FieldParser();
    }

    public function testField()
    {
        $this->parser->parse($this->modelProcessor, new PhpClass());
    }
}