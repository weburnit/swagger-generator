<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor\Validations;

class ClassValidationProcessor extends BaseValidationProcessor
{
    /**
     * {@inheritdoc}
     */
    public function getNextProcessor()
    {
        return null;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }
}