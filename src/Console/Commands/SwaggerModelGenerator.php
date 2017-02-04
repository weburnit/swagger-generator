<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands;

use gossi\codegen\generator\CodeFileGenerator;
use gossi\codegen\model\PhpClass;
use Illuminate\Console\Command;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Weburnit\Console\Commands\Parser\SwaggerClassParser;
use Weburnit\Console\Commands\Processor\Validations\ValidationFactory;
use Weburnit\Console\Commands\Processor\ValueObjectProcessor;

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
        $generator     = new CodeFileGenerator(
            ['declareStrictTypes' => true]
        );
        $rootNamespace = $this->getNamespace();
        $class         = new PhpClass();
        $parser        = new SwaggerClassParser();
        $processor     = new ValueObjectProcessor();
        $fileSystem    = new Filesystem();

        $processor->request($this);
        $processor->setNamespace($rootNamespace);
        $parser->parse($processor, $class);
        $className = $processor->getModelClass();
        $fileSystem->dumpFile($this->source.DIRECTORY_SEPARATOR.$className.'.php', $generator->generate($class));

        foreach ($processor->getProperties() as $property) {
            if (ValidationFactory::TYPE_CLASS === $property->getValue()->getInput()) {
                $subClass               = new PhpClass();
                $propertyClassProcessor = new ValueObjectProcessor(
                    sprintf('Provide Class for field(%s - %s)', $property->getInput(), $property->getDescription())
                );
                $propertyClassProcessor->setDescription($property->getDescription());
                $propertyClassProcessor->setModelClass($property->getValue()->getValue()->getInput());
                $propertyClassProcessor->setNamespace($rootNamespace);
                $propertyClassProcessor->request($this);

                $parser->parse($propertyClassProcessor, $subClass);
                $fileSystem->dumpFile(
                    $this->source.DIRECTORY_SEPARATOR.$propertyClassProcessor->getModelClass().'.php',
                    $generator->generate($subClass)
                );
            }
        }
    }

    /**
     * @param $source
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    protected function extractNameSpace($source)
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
    protected function getNamespace()
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
            implode('\\', array_filter($sources))
        );
    }
}
