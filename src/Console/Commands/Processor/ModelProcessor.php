<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class ModelProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class ModelProcessor extends AbstractProcessor
{
    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var ProcessorResult[]
     */
    private $properties = [];

    public static $propertyIndex = 1;

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $property = parent::request($command);

        $description = $command->ask(
            sprintf('%s description', 'Class'),
            ''
        );
        $property->setDescription($description);

        $this->description = $property->getDescription();

        $this->modelClass = $property->getKey();

        while ($property = $this->addNewProperty($command)) {
            $this->properties[$property->getKey()] = $property;
            static::$propertyIndex++;
        }

        return $this;
    }

    /**
     * @return ProcessorResult[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @return ProcessorInterface | null
     */
    public function getNextProcessor()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return int
     */
    public static function getPropertyIndex(): int
    {
        return self::$propertyIndex;
    }

    /**
     * @param int $propertyIndex
     */
    public static function setPropertyIndex(int $propertyIndex)
    {
        self::$propertyIndex = $propertyIndex;
    }

    /**
     * {@inheritdoc}
     */
    protected function processKey($key): bool
    {
        if (!$key) {
            $this->error('You must provide proper namespace');
            $this->request();
        }

        return true;
    }

    /**
     * @return string
     */
    protected function getQuestion(): string
    {
        return 'Provide your model Model Class';
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }

    /**
     * @param Command $command
     *
     * @return ProcessorResult
     */
    private function addNewProperty(Command $command)
    {
        $propertyProcessor = new PropertyProcessor();

        return $propertyProcessor->request($command);
    }
}
