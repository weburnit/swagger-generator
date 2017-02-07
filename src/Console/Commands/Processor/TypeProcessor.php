<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

/**
 * Class TypeProcessor
 *
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
     *
     * @param string $type
     */
    public function __construct(string $type = ValidationFactory::TYPE_STRING)
    {
        $this->factory = new ValidationFactory();
        $this->type    = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $result = $this->process($command);
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
        return sprintf('provide type for this property: number, string, etc. Current is "%s"', $this->type);
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
    protected function processInputValue($type): bool
    {
        if (in_array($type, $this->getDefaultOptions())) {
            $this->type = $type;
        }
        $this->nextProcessor = $this->factory->createValidation($type);

        return is_object($this->nextProcessor);
    }

    /**
     * @return mixed|string
     */
    public function getDefault()
    {
        return $this->type;
    }
}
