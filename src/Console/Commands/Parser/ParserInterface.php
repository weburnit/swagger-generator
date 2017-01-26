<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use gossi\codegen\model\PhpClass;
use Weburnit\Console\Commands\Processor\ModelProcessorInterface;

/**
 * Class ParserInterface
 * @package Weburnit\Console\Commands\Parser
 */
interface ParserInterface
{
    /**
     * @param ModelProcessorInterface $processor
     * @param PhpClass                $class
     */
    public function parse(ModelProcessorInterface $processor, PhpClass $class);
}
