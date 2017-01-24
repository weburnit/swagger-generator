<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;

/**
 * Class ValidationRule
 * @package Weburnit\Console\Commands\Processor
 */
class ProcessorResult
{
    /**
     * @var string
     */
    private $key;

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
     * @param string      $key
     * @param null|string $value
     */
    public function __construct($key, $value = null)
    {
        $this->key   = $key;
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
    public function getKey(): string
    {
        return $this->key;
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
        $value = $this->getValue()->getKey();
        if ($this->value) {
            $dataType = ValidationFactory::getDataType($this->getValue()->getKey());
            if (!$dataType) {
                $value = sprintf('%s:%s', $this->value->getKey(), (string) $this->value->getValue()->getKey());
                $value = sprintf('%s|%s', $value, $this->value->getValue()->getValue()->getKey());
            }
        }

        if ($this->required) {
            $value = sprintf('%s|%s', $value, 'required');
        }

        return $value;
    }
}
