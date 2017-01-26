<?php
namespace Tests\Weburnit\Unit\Console\Parser;

use gossi\codegen\model\PhpClass;
use Weburnit\Console\Commands\Parser\ValidationParser;

class ValidationParserTest extends AbstractParser
{
    /**
     * @var ValidationAbstractParser
     */
    protected $parser;

    protected function setUp()
    {
        parent::setUp();
        $this->parser = new ValidationParser();
    }

    public function testPrimitiveValidation()
    {
        $this->parser->parse($this->modelProcessor, new PhpClass());
    }
}
