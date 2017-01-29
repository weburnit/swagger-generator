<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

/**
 * Class ResultInterface
 * @package Weburnit\Console\Commands\Processor
 */
interface ResultInterface
{
    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @return string
     */
    public function getInput(): string;

    /**
     * @return mixed|ResultInterface
     */
    public function getValue();
}
