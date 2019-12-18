<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Exception;

use Exception;

class LayerObjectTemplateHandlerNotFoundException extends Exception
{
    public function __construct(string $templateName)
    {
        parent::__construct("The template handler for '$templateName' was not found");
    }
}