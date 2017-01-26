<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class JsonModelProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class JsonModelProcessor extends AbstractProcessor implements ModelProcessorInterface
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $modelClass;

    /**
     * @var string
     */
    protected $jsonContent;

    /**
     * @var
     */
    private $namespace;

    /**
     * @var ProcessorResult[]
     */
    private $properties;

    /**
     * JsonModelProcessor constructor.
     *
     * @param string $jsonContent
     */
    public function __construct(string $jsonContent)
    {
        $this->jsonContent = $jsonContent;
    }


    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        try {
            $jsonObjects = json_decode($this->jsonContent, true);

        } catch (\Exception $e) {
            $command->error('JSON content is not valid');

            return;
        }
        $result = parent::request($command);

        $description = $command->ask(
            sprintf('%s description', 'Model'),
            ''
        );

        $this->description = $description;

        $this->modelClass = $result->getInput();
        foreach ($jsonObjects as $key => $value) {
            $processor          = new JsonPropertyProcessor($key, $value);
            $this->properties[] = $processor->request($command);
        }
    }

    /**
     * @return ProcessorInterface | null
     */
    public function getNextProcessor()
    {
        // TODO: Implement getNextProcessor() method.
    }

    /**
     * @return string
     */
    public function getModelClass(): string
    {
        // TODO: Implement getModelClass() method.
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        // TODO: Implement getDescription() method.
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        // TODO: Implement getNamespace() method.
    }

    /**
     * @return ProcessorResult[]
     */
    public function getProperties(): array
    {
        // TODO: Implement getProperties() method.
    }

    /**
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    protected function processInputValue($key): bool
    {
        // TODO: Implement processInputValue() method.
    }

    /**
     * @return string
     */
    protected function getQuestion(): string
    {
        return 'What is your parent Model?';
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }
}