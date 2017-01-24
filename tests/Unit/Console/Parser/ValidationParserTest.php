<?php
namespace Tests\Unit\Console\Parser;

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
        $result = $this->parser->parse($this->modelProcessor);
        static::assertSame(
            "        'orderNumber'=>'string|required',
        'platformCode'=>'exists:product,platformCode|string|required',
",
            $result
        );
    }
}
