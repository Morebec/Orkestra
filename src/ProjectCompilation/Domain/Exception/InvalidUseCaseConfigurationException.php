<?php


namespace Morebec\Orkestra\ProjectCompilation\Infrastructure\Loader;

class InvalidUseCaseConfigurationException extends \Exception
{
    /**
     * InvalidUseCaseConfigurationException constructor.
     * @param string $message
     */
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
