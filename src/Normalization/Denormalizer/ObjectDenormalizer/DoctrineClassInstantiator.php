<?php

namespace Morebec\Orkestra\Normalization\Denormalizer\ObjectDenormalizer;

use Doctrine\Instantiator\Instantiator;

/**
 * Instantiator based on Doctrine's instantiator.
 */
class DoctrineClassInstantiator implements ClassInstantiatorInterface
{
    public function instantiate(string $className): object
    {
        $instantiator = new Instantiator();

        return $instantiator->instantiate($className);
    }
}
