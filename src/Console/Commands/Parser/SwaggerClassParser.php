<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use gossi\codegen\model\PhpClass;
use Weburnit\Console\Commands\Processor\ModelProcessorInterface;
use Weburnit\Console\Commands\Processor\ValueObjectProcessor;
use Weburnit\PhpDocumentor\Tags\SwaggerTag;

/**
 * Class SwaggerParser
 * @package Weburnit\Console\Commands\Parser
 */
class SwaggerClassParser implements ParserInterface
{
    /**
     * @var FieldParser
     */
    private $fieldParser;

    /**
     * @var ValidationParser
     */
    private $validationParser;

    /**
     * SwaggerClassParser constructor.
     */
    public function __construct()
    {
        $this->fieldParser      = new FieldParser();
        $this->validationParser = new ValidationParser();
    }

    /**
     * {@inheritdoc}
     */
    public function parse(ModelProcessorInterface $processor, PhpClass $class)
    {
        $class->setDescription($processor->getDescription());
        $class->setNamespace($processor->getNamespace());
        $class->setName($processor->getModelClass());
        $class->declareUse('Swagger\Annotations as SWG');
        /**
         * @var $processor ValueObjectProcessor
         */
        $requiredFields = [];
        $required       = '';
        foreach ($processor->getProperties() as $field) {
            if ($field->isRequired()) {
                $requiredFields[] = sprintf('"%s"', $field->getInput());
            }
        }
        if (count($requiredFields)) {
            $required = sprintf(
                ',required={%s}',
                implode(',', $requiredFields)
            );
        }

        $swaggerContent = sprintf(
            'SWG\Definition(definition="%s", description="%s", type="object"%s)',
            $processor->getModelClass(),
            $processor->getDescription(),
            $required
        );
        $class->getDocblock()->appendTag(new SwaggerTag($swaggerContent));

        $this->fieldParser->parse($processor, $class);

        $this->validationParser->parse($processor, $class);
    }
}
