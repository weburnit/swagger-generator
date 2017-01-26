<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor\Validations;

use Weburnit\Console\Commands\Processor\AbstractProcessor;

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
    public function getDefault()
    {
        return false;
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
}
