<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor\Validations;

/**
 * Class ExtendedValidationProcessor
 * @package Weburnit\Console\Commands\Processor\Validations
 */
class ExtendedValidationProcessor extends BaseValidationProcessor
{
    /**
     * {@inheritdoc}
     */
    public function getNextProcessor()
    {
        return new BaseValidationProcessor('What is your data type?');
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }
}
