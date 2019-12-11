<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Exception;


use Throwable;

class ModuleNotFoundException extends \Exception
{
    public function __construct(string $moduleName, $code = 0, Throwable $previous = null)
    {
        parent::__construct("The module '$moduleName' was not found in project.", $code, $previous);
    }
}