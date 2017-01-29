<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor\Validations;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\AbstractProcessor;
use Weburnit\Console\Commands\Processor\ProcessorResult;

/**
 * Class BaseProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class BaseValidationProcessor extends AbstractProcessor
{
    /**
     * @var string | null
     */
    private $question;

    /**
     * BaseProcessor constructor.
     *
     * @param string $question
     */
    public function __construct(string $question = '')
    {
        $this->question = $question;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextProcessor()
    {
        return null;
    }

    /**
     * @return string
     */
    protected function getQuestion(): string
    {
        return $this->question;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return ValidationFactory::getValidationOptions();
    }

    /**
     * {@inheritdoc}
     */
    protected function processInputValue($key): bool
    {
        return true;
    }

    /**
     * @param Command $command
     *
     * @return ProcessorResult
     */
    public function request(Command $command)
    {
        return $this->process($command);
    }
}
