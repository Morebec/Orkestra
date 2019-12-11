<?php


namespace Morebec\Orkestra\Core\Infrastructure\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddMessageHandlersToMessageBusCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('messenger.message_handler') as $name => $definition) {

        }
    }
}