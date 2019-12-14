<?php 

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity;

use Assert\Assertion;
use Morebec\ValueObjects\StringBasedValueObject;

/**
 * Namespace
 */
class NamespaceVO extends StringBasedValueObject
{
    function __construct(string $name)
    {
        Assertion::notBlank($name, 'A namespace cannot be blank');
        parent::__construct($name);
    }
    
    /**
     * Returns a new Namespace with the provided string appended
     * Side-Effect-Free-Function
     * @param string $name
     * @return NamespaceVO
     */
    public function appendString(string $name): NamespaceVO
    {
        $newName = (string)$this . '\\' . $name;
        return new static($newName);
    }
}
