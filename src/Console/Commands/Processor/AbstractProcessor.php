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
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        if ($this->getQuestion()) {
            $options  = $this->getDefaultOptions();
            $key      = count($options) ? $command->choice($this->getQuestion(), $options, $this->getDefault()) :
                $command->anticipate($this->getQuestion(), [], $this->getDefault());
            $continue = $this->processKey($key);
            if (!$continue) {
                return;
            }
        }
        $result = $this->getNextProcessor() ? $this->getNextProcessor()->request($command) : null;

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
    abstract protected function processKey($key): bool;

    /**
     * @return string
     */
    abstract protected function getQuestion(): string;

    /**
     * @return array
     */
    abstract protected function getDefaultOptions(): array;
}
