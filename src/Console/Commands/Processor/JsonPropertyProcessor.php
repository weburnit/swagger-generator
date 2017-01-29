<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

/**
 * Class JsonPropertyProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class JsonPropertyProcessor extends AbstractProcessor
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var mixed
     */
    private $value;

    private $factory;

    /**
     * JsonPropertyProcessor constructor.
     *
     * @param string $property
     * @param mixed  $value
     */
    public function __construct(string $property, $value = null)
    {
        $this->property = $property;
        $this->value    = $value;
        $this->factory  = new ValidationFactory();
    }

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $processor = $this->process($command);

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
     * @return ProcessorInterface
     */
    public function getNextProcessor()
    {
        $type = $this->detectDataType($this->value);

        if (ValidationFactory::TYPE_ARRAY === $type) {
            return new JsonModelProcessor(
                end($this->value),
                sprintf('Provide your class name for field(%s)', $this->property)
            );
        }

        if ($validation = $this->factory->createValidation($type)) {
            return $validation;
        }

        return new TypeProcessor($type);
    }

    /**
     * {@inheritdoc}
     */
    public function getDefault()
    {
        return $this->property;
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    protected function processInputValue($input): bool
    {
        if ($input && $input !== $this->property) {
            $this->property = $input;
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getQuestion(): string
    {
        return sprintf('Rename field(%s) or Press Enter to ignore', $this->property);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [$this->property];
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private function detectDataType($value)
    {
        if (is_bool($value)) {
            return ValidationFactory::TYPE_BOOLEAN;
        }
        if (is_array($value)) {
            return ValidationFactory::TYPE_ARRAY;
        }
        if (is_numeric($value)) {
            if ((int) $value == $value) {
                return ValidationFactory::TYPE_INTEGER;
            }

            return ValidationFactory::TYPE_NUMERIC;
        }

        if ((bool) strtotime($value)) {
            return ValidationFactory::TYPE_DATE;
        }

        if (is_array($value)) {
            $keys = array_keys($value);
            if (!is_numeric(current($keys))) {
                return ValidationFactory::TYPE_ARRAY;
            }

            return ValidationFactory::TYPE_CLASS;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return ValidationFactory::TYPE_EMAIL;
        }

        return ValidationFactory::TYPE_STRING;
    }
}
