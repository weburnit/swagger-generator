<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpProperty;
use Weburnit\Console\Commands\Processor\JsonModelProcessor;
use Weburnit\Console\Commands\Processor\ModelProcessorInterface;
use Weburnit\Console\Commands\Processor\ResultInterface;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;
use Weburnit\PhpDocumentor\Tags\SwaggerTag;

/**
 * Class FieldParser
 * @package Weburnit\Console\Commands\Parser
 */
class FieldParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(ModelProcessorInterface $processor, PhpClass $class)
    {
        $properties = [];
        foreach ($processor->getProperties() as $field) {

            $dataType = $this->detectDataType($field);
            $property = PhpProperty::create($field->getInput())
                ->setVisibility('protected')
                ->setDescription($field->getDescription());
            $property->getDocblock()->appendTag(
                new SwaggerTag(sprintf('SWG\Property(description="%s")', $field->getDescription()))
            );
            $property->setType($field->isRequired() ? $dataType : sprintf('%s|null', $dataType));

            $properties[] = $property;
        }

        $class->setProperties($properties);
    }

    private function detectDataType(ResultInterface $field)
    {
        if (ValidationFactory::TYPE_ARRAY === $field->getValue()->getInput()) {
            return $field->getValue()->getValue()->getInput();
        }
        if (ValidationFactory::TYPE_CLASS === $field->getValue()->getInput() &&
            $field->getValue()->getValue() instanceof JsonModelProcessor
        ) {
            return $field->getValue()->getValue()->getModelClass();
        }
        $dataType = ValidationFactory::getDataType($field->getValue()->getInput());

        if (!$dataType) {
            $dataType = $field->getValue()->getValue()->getValue()->getInput();

            return $dataType;
        }

        return $dataType;
    }
}
