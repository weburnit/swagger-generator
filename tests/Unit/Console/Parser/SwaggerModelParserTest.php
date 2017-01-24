<?php
namespace Tests\Unit\Console\Parser;

use Weburnit\Console\Commands\Parser\SwaggerClassParser;

/**
 * Class SwaggerModelParserTest
 * @package Tests\Unit\Console\Parser
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
        $result = $this->parser->parse($this->modelProcessor);

        $classContent = '<?php
declare(strict_types = 1);

namespace Weburnit\ValueObjects;

use Swagger\Annotations as SWG;

/**
* Product Description
*
* @SWG\Definition(
*     definition="Product",
*     description="Product Description",
*     type="object",
*     required={"orderNumber","platformCode"}
* )
*/
class Product
{
    public static $validation = [        \'orderNumber\'=>\'string|required\',
        \'platformCode\'=>\'exists:product,platformCode|string|required\',
];

    /**
    * @var string
    * @SWG\Property(property="orderNumber", type="string", description="Order Number")
    */
    private $orderNumber;
    /**
    * @var string
    * @SWG\Property(property="platformCode", type="string", description="platform code")
    */
    private $platformCode;

}
';
        static::assertEquals($classContent, $result, 'Class must reflect this content');
    }
}