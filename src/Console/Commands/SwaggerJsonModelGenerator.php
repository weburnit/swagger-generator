<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use Symfony\Component\Filesystem\Filesystem;
use Weburnit\Console\Commands\Parser\SwaggerClassParser;
use Weburnit\Console\Commands\Processor\JsonModelProcessor;

/**
 * Class SwaggerGenerator
 *
 * @package           Weburnit\Console\Commands
 *
 * @codeCoverageIgnore*
 *
 * @usage             php artisan swagger:json_model app/PathTo/YourFolder app/PathTo/YourJsonFile
 */
class SwaggerJsonModelGenerator extends SwaggerModelGenerator
{
    /**
     * @var string
     */
    protected $signature = 'swagger:json_model {src} {json}';

    /**
     * @var string
     */
    protected $description = 'Generate Model within Swagger document and validation';

    /**
     * @var string
     */
    private $source;

    /**
     * Handle command
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \RuntimeException
     */
    public function handle()
    {
        $codeGenerator = new CodeFileGenerator();
        $rootNamespace = $this->getNamespace();
        $jsonContent   = $this->getJsonContent();

        $processor = new JsonModelProcessor(json_decode($jsonContent, true));
        $processor->request($this);
        $processor->setNamespace($rootNamespace);

        $parser = new SwaggerClassParser();
        $model  = new PhpClass();
        $parser->parse($processor, $model);
        $className  = $processor->getModelClass();
        $fileSystem = new Filesystem();

        $fileSystem->dumpFile(
            $this->argument('src').DIRECTORY_SEPARATOR.$className.'.php',
            $codeGenerator->generate($model)
        );

        foreach ($processor->getProperties() as $property) {
            if ($property->getValue() instanceof JsonModelProcessor) {
                $this->comment('Writing sub field');
                $fileSystem->dumpFile(
                    $this->argument('src').DIRECTORY_SEPARATOR.$property->getValue()->getModelClass().'.php',
                    $codeGenerator->generate($model)
                );
            }
        }
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    private function getJsonContent(): string
    {
        $jsonFile = $this->argument('json');
        if (!file_exists($jsonFile)) {
            $jsonFile = sprintf('%s%s%s', __DIR__, DIRECTORY_SEPARATOR, $jsonFile);
        }
        if (file_exists($jsonFile)) {
            return file_get_contents($jsonFile);
        }
        throw new \RuntimeException('JSON file path is not correct');
    }
}
