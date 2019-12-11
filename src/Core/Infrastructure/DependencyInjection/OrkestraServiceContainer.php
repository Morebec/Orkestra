<?php

namespace Morebec\Orkestra\Core\Infrastructure\DependencyInjection;

use Symfony\Component\Config\FileLocator as SymfonyFileLocator;
use Symfony\Component\DependencyInjection\Compiler\AutowirePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Messenger\DependencyInjection\MessengerPass;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBus;

/**
 * Dependency Injection Container for Orkestra services
 */
class OrkestraServiceContainer extends ContainerBuilder
{
    public function __construct()
    {
        parent::__construct(null);        
    }
    
    public function build(): void
    {
        $loader = new YamlFileLoader(
                $this, 
                new SymfonyFileLocator(__DIR__ . '/../../../../config')
        );
        
        $loader->load('services.yaml');

        // Message Bus
        $this->setParameter(MessageBus::class . '.middleware', [['id' => 'handle_message']]);
        $this->registerForAutoconfiguration(MessageHandlerInterface::class)
            ->addTag('messenger.message_handler');

        $this->addCompilerPass(new MessengerPass());

        // ConsoleCommands
        $this->addCompilerPass(new AddConsoleCommandsCompilerPass());

        $this->compile();
    }
}
