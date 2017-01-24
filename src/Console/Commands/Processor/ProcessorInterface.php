<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class ProcessorInterface
 * @package Weburnit\Console\Commands\Processor
 */
interface ProcessorInterface
{
    /**
     * @param Command $command
     *
     * @return ProcessorResult
     */
    public function request(Command $command);

    /**
     * @return ProcessorInterface | null
     */
    public function getNextProcessor();
}
