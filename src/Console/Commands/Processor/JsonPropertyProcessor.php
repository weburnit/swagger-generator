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

    /**
     * @var ValidationFactory
     */
    private $factory;

    /**
     * @var string
     */
    private $type;

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
        $this->type     = $this->detectDataType($this->value);
    }

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $result = $this->process($command);

        if ($result) {
            if (in_array($this->type, [ValidationFactory::TYPE_ARRAY, ValidationFactory::TYPE_CLASS])) {
                $result = new ProcessorResult(
                    $this->property,
                    new ProcessorResult($this->type, $result->getValue())
                );
            }
            $description = $command->ask(
                sprintf('%s description', 'Property'),
                $this->property
            );
            $result->setDescription($description);

            $required = $command->askWithCompletion('Is required?(Y/N) Default (N)', ['Y', 'N'], 'N');
            $result->setRequired(strtoupper($required) === 'Y');
        }

        return $result;
    }

    /**
     * @return ProcessorInterface
     */
    public function getNextProcessor()
    {
        if (ValidationFactory::TYPE_ARRAY === $this->type && is_array($this->value)) {
            $schema     = end($this->value);
            $keys       = array_keys($schema);
            $class      = end($keys);
            $jsonObject = end($schema);
            $processor  = new JsonModelProcessor(
                $jsonObject,
                sprintf('Provide your class name for field(%s)', $this->property)
            );
            $processor->setModelClass($class);

            return $processor;
        }

        if (ValidationFactory::TYPE_CLASS === $this->type) {
            $processor = new JsonModelProcessor(
                $this->value,
                sprintf('Provide your class name for(%s)', $this->property)
            );

            $processor->setModelClass(ucfirst($this->property));

            return $processor;
        }

        return new TypeProcessor($this->type);
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
        if (is_array($value)) {
            $keys = count(array_keys($value));
            if (1 === $keys) {
                return ValidationFactory::TYPE_ARRAY;
            }

            return ValidationFactory::TYPE_CLASS;
        }
        if (is_bool($value)) {
            return ValidationFactory::TYPE_BOOLEAN;
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

        if (!filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            return ValidationFactory::TYPE_EMAIL;
        }

        return ValidationFactory::TYPE_STRING;
    }
}
