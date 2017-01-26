<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands;

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
        $rootNamespace = $this->getNamespace();
        $processor     = new JsonModelProcessor($this->getJsonContent());
        $processor->request($this);
        $processor->setNamespace($rootNamespace);

        $parser     = new SwaggerClassParser();
        $content    = $parser->parse($processor);
        $className  = $processor->getModelClass();
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($this->source.DIRECTORY_SEPARATOR.$className.'.php', $content);
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    private function getJsonContent(): string
    {
        $jsonFile = $this->argument('src');
        if (!file_exists($jsonFile)) {
            $jsonFile = sprintf('%s%s%s', __DIR__, DIRECTORY_SEPARATOR, $jsonFile);
        }
        if (file_exists($jsonFile)) {
            return file_get_contents($jsonFile);
        }
        throw new \RuntimeException('JSON file path is not correct');
    }
}
