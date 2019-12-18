<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Exception;

use Exception;

class ModuleObjectTemplateHandlerNotFoundException extends Exception
{
    public function __construct(string $templateName)
    {
        parent::__construct("The template handler for '$templateName' was not found");
    }
}
