<?php
namespace Tests\Unit\Console\Parser;


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
        $result         = $this->parser->parse($this->modelProcessor);
        $expectedResult =
'    /**
    * @var string
    * @SWG\Property(property="orderNumber", type="string", description="Order Number")
    */
    private $orderNumber;
    /**
    * @var string
    * @SWG\Property(property="platformCode", type="string", description="platform code")
    */
    private $platformCode;
';

        static::assertEquals($expectedResult, $result, 'Must reflect format');
    }
}