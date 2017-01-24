<?php
declare(strict_types = 1);

namespace Weburnit\Console\Commands\Parser\Template;

/**
 * Class Util
 * @package Weburnit\Console\Commands\Parser\Template
 */
class Util
{
    const TEMPLATE_CLASS       = 'class.tpl';
    const TEMPLATE_INFORMATION = 'information.tpl';
    const TEMPLATE_VALIDATION  = 'validation.tpl';
    const TEMPLATE_FIELD       = 'field.tpl';

    /**
     * @param string $template
     * @param string $key
     * @param string $content
     *
     * @return string
     */
    public static function update(string $template, string $key, string $content): string
    {
        return str_replace(sprintf('{%s}', $key), $content, $template);
    }

    /**
     * @param string $template
     *
     * @return string
     */
    public static function getTemplate(string $template): string
    {
        return file_get_contents(__DIR__.DIRECTORY_SEPARATOR.$template);
    }
}
