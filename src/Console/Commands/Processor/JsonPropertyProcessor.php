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
     * JsonPropertyProcessor constructor.
     *
     * @param string $property
     * @param mixed  $value
     */
    public function __construct(string $property, $value = null)
    {
        $this->property = $property;
        $this->value    = $value;
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
     * @return ProcessorInterface
     */
    public function getNextProcessor(): ProcessorInterface
    {
        $type = $this->detectDataType($this->value);

        if (in_array($type, ValidationFactory::getValidationOptions())) {
            return new TypeProcessor($type);
        }

        return null;
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
        return sprintf('Enter for keep using default(%s) property\'s name or type your proper one', $this->property);
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private function detectDataType($value)
    {
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

        return ValidationFactory::TYPE_STRING;
    }
}