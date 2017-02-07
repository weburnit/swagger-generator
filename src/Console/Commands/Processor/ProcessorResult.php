<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

/**
 * Class ValidationRule
 * @package Weburnit\Console\Commands\Processor
 */
class ProcessorResult implements ResultInterface
{
    /**
     * @var string
     */
    private $input;

    /**
     * @var string | null | ProcessorResult
     */
    private $value;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $required;

    /**
     * ValidationRule constructor.
     *
     * @param string                      $input
     * @param null|string|ProcessorResult $value
     */
    public function __construct($input, $value = null)
    {
        $this->input = $input;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->input;
    }

    /**
     * @return null|string|ProcessorResult
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $value = '';
        if ($this->value) {
            $value    = $this->getValue()->getInput() === 'class' ?
                ValidationFactory::TYPE_ARRAY : $this->getValue()->getInput();
            $dataType = ValidationFactory::getDataType($this->getValue()->getInput());
            if (!$dataType) {
                $value = sprintf('%s:%s', $this->value->getInput(), (string) $this->value->getValue()->getInput());
                $value = sprintf('%s|%s', $value, $this->value->getValue()->getValue()->getInput());
            }
        }

        if ($this->required) {
            $value = sprintf('%s|%s', $value, 'required');
        }

        return $value;
    }
}
