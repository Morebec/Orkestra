<?php

namespace Morebec\Orkestra\Normalization\Denormalizer\ObjectDenormalizer;

use Morebec\Orkestra\Normalization\Denormalizer\DenormalizationContextInterface;

class ClassPropertyDenormalizationContext implements ClassPropertyDenormalizationContextInterface
{
    private $propertyName;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var DenormalizationContextInterface
     */
    private $parentContext;

    /**
     * ClassPropertyDenormalizationContext constructor.
     *
     * @param mixed $value
     */
    public function __construct(string $propertyName, $value, DenormalizationContextInterface $parentContext)
    {
        $this->propertyName = $propertyName;
        $this->value = $value;
        $this->parentContext = $parentContext;
    }

    public function getClassName(): string
    {
        return $this->parentContext->getTypeName();
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getParentContext(): ?DenormalizationContextInterface
    {
        return $this->parentContext;
    }

    public function getTypeName(): string
    {
        // TODO Define a way to make this undefined.
    }
}
