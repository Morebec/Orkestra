<?php

namespace Morebec\Orkestra\Messaging\Normalization;

/**
 * The domain message class map represents a mapping of message type names and PHP types.
 * E.g.: user.created => UserCreatedEvent::class.
 */
class DomainMessageClassMap implements DomainMessageClassMapInterface
{
    /** @var string[] */
    private $classMap;

    public function __construct(iterable $mappings = [])
    {
        $this->classMap = [];
        foreach ($mappings as $key => $item) {
            $this->addMapping($key, $item);
        }
    }

    public function addMapping(string $domainMessageTypeName, string $domainMessageClassName): void
    {
        $this->classMap[$domainMessageTypeName] = $domainMessageClassName;
    }

    public function getClassNameForDomainMessageTypeName(string $domainMessageTypeName): ?string
    {
        if (!\array_key_exists($domainMessageTypeName, $this->classMap)) {
            return null;
        }

        return $this->classMap[$domainMessageTypeName];
    }

    public function toArray(): array
    {
        return $this->classMap;
    }
}
