<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser;

use Weburnit\Console\Commands\Processor\ModelProcessor;

/**
 * Class ParserInterface
 * @package Weburnit\Console\Commands\Parser
 */
interface ParserInterface
{
    /**
     * @param ModelProcessor $processor
     *
     * @return string
     */
    public function parse(ModelProcessor $processor): string;
}
