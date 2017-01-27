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
            /**
             * @var $jsonObjects array
             */
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
     * @return ProcessorResult[]
     */
    public function getProperties(): array
    {
        return $this->properties;
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
        return true;
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