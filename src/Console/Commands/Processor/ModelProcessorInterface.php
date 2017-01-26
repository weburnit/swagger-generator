<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

/**
 * Interface ModelProcessorInterface
 * @package Weburnit\Console\Commands\Processor
 */
interface ModelProcessorInterface
{
    /**
     * @return string
     */
    public function getModelClass(): string;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return string
     */
    public function getNamespace(): string;

    /**
     * @return ProcessorResult[]
     */
    public function getProperties(): array;
}
