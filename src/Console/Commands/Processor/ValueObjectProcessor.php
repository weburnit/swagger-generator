<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class ModelProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class ValueObjectProcessor extends AbstractProcessor implements ModelProcessorInterface
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
     * @var string
     */
    private $question;

    /**
     * @var ProcessorResult[]
     */
    private $properties = [];

    public static $propertyIndex = 1;

    /**
     * ValueObjectProcessor constructor.
     *
     * @param string $question
     */
    public function __construct(string $question = null)
    {
        $this->question = $question ?? 'Provide your Model Class';
    }

    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        if (!$this->description && !$this->modelClass) {
            $property = parent::request($command);

            $description = $command->ask(
                sprintf('%s description', 'Class'),
                ''
            );

            $this->description = $description;

            $this->modelClass = $property->getInput();
        }
        $command->info(sprintf('Adding properties for class %s(%s)', $this->modelClass, $this->description));

        while ($property = $this->addNewProperty($command)) {
            $this->properties[$property->getInput()] = $property;
            static::$propertyIndex++;
        }
    }

    /**
     * @return ProcessorResult[]
     */
    public function getProperties(): array
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
     * {@inheritdoc}
     */
    public function getModelClass(): string
    {
        return $this->modelClass;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param string $modelClass
     */
    public function setModelClass(string $modelClass)
    {
        $this->modelClass = $modelClass;
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
    protected function processInputValue($key): bool
    {
        if (!$key) {
            $this->request();
        }

        return true;
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
