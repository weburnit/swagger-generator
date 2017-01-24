<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use Weburnit\Console\Commands\Parser\Template\Util;
use Weburnit\Console\Commands\Processor\ModelProcessor;

/**
 * Class ValidationParser
 * @package Weburnit\Console\Commands\Parser
 */
class ValidationParser implements ParserInterface
{
    /**
     * @var string
     */
    private $template;

    /**
     * FieldParser constructor.
     */
    public function __construct()
    {
        $this->template = Util::getTemplate(Util::TEMPLATE_VALIDATION);
    }

    /**
     * @param ModelProcessor $processor
     *
     * @return string
     */
    public function parse(ModelProcessor $processor): string
    {
        $validations = [];
        foreach ($processor->getProperties() as $processorResult) {
            $template      = $this->template;
            $template      = Util::update($template, 'field', $processorResult->getKey());
            $template      = Util::update($template, 'validation', (string) $processorResult);
            $validations[] = $template;
        }

        return implode('', $validations);
    }
}
