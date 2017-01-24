<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use Weburnit\Console\Commands\Parser\Template\Util;
use Weburnit\Console\Commands\Processor\ModelProcessor;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

/**
 * Class FieldParser
 * @package Weburnit\Console\Commands\Parser
 */
class FieldParser implements ParserInterface
{
    private $template;

    /**
     * FieldParser constructor.
     */
    public function __construct()
    {
        $this->template = Util::getTemplate(Util::TEMPLATE_FIELD);
    }

    /**
     * @param ModelProcessor $processor
     *
     * @return string
     */
    public function parse(ModelProcessor $processor): string
    {
        $templates = '';
        foreach ($processor->getProperties() as $processorResult) {
            $template = $this->template;
            $template = Util::update($template, 'field', $processorResult->getKey());
            $template = Util::update($template, 'description', $processorResult->getDescription());

            $dataType = ValidationFactory::getDataType($processorResult->getValue()->getKey());
            if (!$dataType) {
                $dataType = $processorResult->getValue()->getValue()->getValue()->getKey();
            }
            $template = Util::update(
                $template,
                'type',
                $dataType
            );
            $template = Util::update(
                $template,
                'varType',
                $processorResult->isRequired() ? $dataType : sprintf('%s|null', $dataType)
            );

            $templates .= $template;
        }

        return $templates;
    }
}
