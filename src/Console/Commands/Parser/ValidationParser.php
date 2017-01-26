<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use gossi\codegen\model\PhpClass;
use gossi\codegen\model\PhpProperty;
use Weburnit\Console\Commands\Processor\ModelProcessorInterface;

/**
 * Class ValidationParser
 * @package Weburnit\Console\Commands\Parser
 */
class ValidationParser implements ParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(ModelProcessorInterface $processor, PhpClass $class)
    {
        $validations = [];
        foreach ($processor->getProperties() as $processorResult) {
            $template      = sprintf('\'%s\'=>\'%s\',', $processorResult->getInput(), (string) $processorResult);
            $validations[] = $template;
        }

        $class->setProperty(
            PhpProperty::create('validation')->setVisibility('public')
                ->setStatic(true)
                ->setType('array')
                ->setExpression(sprintf('[%s]', implode('', $validations)))
        );
    }
}
