<?php
namespace Tests\Weburnit\Unit\Console\Parser;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpClass;
use Weburnit\Console\Commands\Parser\SwaggerClassParser;

/**
 * Class SwaggerModelParserTest
 *
 * @package Tests\Weburnit\Unit\Console\Parser
 */
class SwaggerModelParserTest extends AbstractParser
{
    /**
     * @var SwaggerClassParser
     */
    private $parser;

    protected function setUp()
    {
        parent::setUp();
        $this->modelProcessor->method('getNamespace')->willReturn('Weburnit\\ValueObjects');
        $this->parser = new SwaggerClassParser();
    }

    public function testClassValue()
    {
        $class = new PhpClass();
        $this->parser->parse($this->modelProcessor, $class);

        $generator    = new CodeGenerator();
        $classContent = 'namespace Weburnit\ValueObjects;

use Swagger\Annotations as SWG;

/**
 * Product Description
 * 
 * @SWG\Definition(definition="Product", description="Product Description", type="object",required={"orderNumber","platformCode"})
 */
class Product {

	/**
	 * @var array
	 */
	public static $validation = [\'orderNumber\'=>\'bail|required|string\',\'platformCode\'=>\'bail|required|exists:product,platformCode|string\',];

	/**
	 * Order Number
	 * 
	 * @SWG\Property(description="Order Number", type="string")
	 * @var string
	 */
	protected $orderNumber;

	/**
	 * platform code
	 * 
	 * @SWG\Property(description="platform code", type="string")
	 * @var string
	 */
	protected $platformCode;
}';

        static::assertEquals($classContent, $generator->generate($class));
    }
}