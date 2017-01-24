<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

/**
 * Class TypeProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class TypeProcessor extends AbstractProcessor
{
    /**
     * @var ValidationFactory
     */
    private $factory;

    /**
     * @var ProcessorInterface
     */
    private $nextProcessor;

    /**
     * @var string
     */
    private $type;

    /**
     * TypeProcessor constructor.
     */
    public function __construct()
    {
        $this->factory = new ValidationFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $result = parent::request($command);
        if (!$result) {
            $result = new ProcessorResult($this->type);
        }

        return $result;
    }

    /**
     * @return ProcessorInterface
     */
    public function getNextProcessor()
    {
        return $this->nextProcessor;
    }

    /**
     * @return string
     */
    protected function getQuestion(): string
    {
        return 'provide type for this property: number, string...';
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
    protected function processKey($type): bool
    {
        $this->type          = $type;
        $this->nextProcessor = $this->factory->createValidation($type);

        return is_object($this->nextProcessor);
    }
}
