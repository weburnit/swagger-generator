<?php
namespace Weburnit\PhpDocumentor\Tags;

use gossi\docblock\tags\AbstractTypeTag;

class SwaggerTag extends AbstractTypeTag
{
    public function __construct($content = '')
    {
        parent::__construct($content, '');
    }
}