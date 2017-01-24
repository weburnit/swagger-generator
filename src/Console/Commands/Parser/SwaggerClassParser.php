<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use Weburnit\Console\Commands\Parser\Template\Util;
use Weburnit\Console\Commands\Processor\ModelProcessor;

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
     * @param ModelProcessor $processor
     *
     * @return string
     */
    public function parse(ModelProcessor $processor): string
    {
        /**
         * @var $processor ModelProcessor
         */
        $template    = Util::getTemplate(Util::TEMPLATE_CLASS);
        $information = Util::getTemplate(Util::TEMPLATE_INFORMATION);

        $information    = Util::update($information, 'information', $processor->getDescription());
        $information    = Util::update($information, 'class', $processor->getModelClass());
        $requiredFields = [];
        $required       = '';
        foreach ($processor->getProperties() as $field) {
            if ($field->isRequired()) {
                $requiredFields[] = sprintf('"%s"', $field->getKey());
            }
        }
        if (count($requiredFields)) {
            $required    = sprintf(
                ',
*     required={%s}',
                implode(',', $requiredFields)
            );
            $information = Util::update($information, 'required', $required);
        }


        $template = Util::update($template, 'class_information', $information);

        $fields   = $this->fieldParser->parse($processor);
        $template = Util::update($template, 'fields', $fields);

        $validations = $this->validationParser->parse($processor);
        $template    = Util::update($template, 'validation', $validations);

        $template = Util::update($template, 'namespace', $processor->getNamespace());
        $template = Util::update($template, 'class', $processor->getModelClass());

        return $template;
    }
}
