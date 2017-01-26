<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class PropertyProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class PropertyProcessor extends AbstractProcessor
{
    /**
     * @return ProcessorInterface
     */
    public function getNextProcessor(): ProcessorInterface
    {
        return new TypeProcessor();
    }

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $processor = parent::request($command);

        if ($processor) {
            $description = $command->ask(
                sprintf('%s description', 'Property'),
                ''
            );
            $processor->setDescription($description);

            $required = $command->askWithCompletion('Is required?(Y/N) Default (N)', ['Y', 'N'], 'N');
            $processor->setRequired(strtoupper($required) === 'Y');
        }

        return $processor;
    }

    /**
     * @return string
     */
    protected function getQuestion(): string
    {
        return 'Enter your property name such as isFinished, orderNumber';
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function processInputValue($key): bool
    {
        return !empty($key);
    }
}
