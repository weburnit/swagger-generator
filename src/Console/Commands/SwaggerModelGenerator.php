<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands;

use Illuminate\Console\Command;
use Weburnit\Console\Commands\Parser\SwaggerClassParser;
use Weburnit\Console\Commands\Processor\ModelProcessor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

/**
 * Class SwaggerGenerator
 *
 * @package           Weburnit\Console\Commands
 *
 * @codeCoverageIgnore*
 *
 * @usage             php artisan swagger:model app/PathTo/YourModel
 */
class SwaggerModelGenerator extends Command
{
    /**
     * @var string
     */
    protected $signature = 'swagger:model {src}';

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
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function handle()
    {
        $rootNamespace = $this->getNamespace();
        $processor     = new ModelProcessor();
        $processor->request($this);
        $processor->setNamespace($rootNamespace);

        $parser     = new SwaggerClassParser();
        $content    = $parser->parse($processor);
        $className  = $processor->getModelClass();
        $fileSystem = new Filesystem();
        $fileSystem->dumpFile($this->source.DIRECTORY_SEPARATOR.$className.'.php', $content);
    }

    /**
     * @param $source
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    private function extractNameSpace($source)
    {
        $finder = new Finder();
        $finder->files()->in($source)->name('*.php');

        if (!$finder->count()) {
            $sourceStructure = explode(DIRECTORY_SEPARATOR, $source);
            $lastFolder      = end($sourceStructure);

            return $this->extractNameSpace(str_replace($lastFolder, '', $source));
        }
        $path       = '';
        $namespaces = [];
        foreach ($finder as $file) {
            $path    = $file->getRealPath();
            $content = file_get_contents($path);

            preg_match('/namespace\s(.*)\;/', $content, $namespaces);
        }
        $namespace = explode('\\', $namespaces[1]);

        $sources        = explode(DIRECTORY_SEPARATOR, $path);
        $rootNamespaces = array_diff($namespace, $sources);

        $root = end($rootNamespaces);

        return $root;
    }

    /**
     * @return string
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    private function getNamespace()
    {
        $this->source = $this->argument('src');
        $sources      = explode(DIRECTORY_SEPARATOR, $this->source);
        array_shift($sources);
        $fileSystem = new Filesystem();
        if (!$fileSystem->exists($this->source)) {
            $fileSystem->mkdir($this->source);
        }
        $root = $this->extractNameSpace($this->source);

        return sprintf(
            '%s\\%s',
            $root,
            implode('\\', $sources)
        );
    }
}
