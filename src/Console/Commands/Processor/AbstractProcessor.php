<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class AbstractProcessor
 * @package Weburnit\Console\Commands\Processor
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @param Command $command
     *
     * @return void|ProcessorResult
     */
    protected function process(Command $command)
    {
        if ($this->getQuestion()) {
            $key      = $this->processInput($command);
            $continue = $this->processInputValue($key);
            if (!$continue) {
                return;
            }
        }
        $nextProcess = $this->getNextProcessor();
        $result      = $nextProcess ? $nextProcess->request($command) : null;

        $processor = new ProcessorResult($key, $result);

        return $processor;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return false;
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    abstract protected function processInputValue($key): bool;

    /**
     * @return string
     */
    abstract protected function getQuestion(): string;

    /**
     * @return array
     */
    abstract protected function getDefaultOptions(): array;

    /**
     * @param Command $command
     *
     * @return string
     */
    private function processInput(Command $command)
    {
        $options = $this->getDefaultOptions();
        if (count($options)) {
            $command->line('Options: '.implode(', ', $options), 'comment');
        }
        $key = $command->anticipate(
            $this->getQuestion(),
            array_filter($options),
            $this->getDefault()
        );

        return $key;
    }
}
