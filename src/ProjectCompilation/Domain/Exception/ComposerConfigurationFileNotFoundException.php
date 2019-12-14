<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Exception;


use Throwable;

class ComposerConfigurationFileNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('composer.json could not be found');
    }
}