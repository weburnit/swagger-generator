<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Processor;

use Illuminate\Console\Command;

/**
 * Class JsonModelProcessor
 * @package Weburnit\Console\Commands\Processor
 */
class JsonModelProcessor extends AbstractProcessor implements ModelProcessorInterface, ResultInterface
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
     * @var array
     */
    protected $jsonObject;

    /**
     * @var
     */
    private $namespace;

    /**
     * @var ProcessorResult[]
     */
    private $properties;

    /**
     * @var string
     */
    private $question;

    /**
     * JsonModelProcessor constructor.
     *
     * @param array  $jsonObject
     * @param string $question
     */
    public function __construct(array $jsonObject, string $question = null)
    {
        $this->jsonObject = $jsonObject;
        $this->question   = $question;
    }


    /**
     * {@inheritdoc}
     */
    public function request(Command $command)
    {
        $result = $this->process($command);

        $description = $command->ask(
            sprintf('%s description', 'Model'),
            ''
        );

        $this->description = $description;

        $this->modelClass = $result->getInput();
        foreach ($this->jsonObject as $key => $value) {
            $processor          = new JsonPropertyProcessor($key, $value);
            $this->properties[] = $processor->request($command);
        }

        return $this;
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
        if ($this->question) {
            return $this->question;
        }

        return 'What is your parent Model?';
    }

    /**
     * @return array
     */
    protected function getDefaultOptions(): array
    {
        return [];
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getInput(): string
    {
        return $this->modelClass;
    }

    /**
     * @return mixed|ResultInterface
     */
    public function getValue()
    {
        return $this;
    }
}
