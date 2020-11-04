<?php

namespace Morebec\Orkestra\Normalization\Denormalizer\ObjectDenormalizer;

use Doctrine\Instantiator\Instantiator;

/**
 * Class Instantiator based off Doctrine Instantiator.
 */
class DoctrineInstantiator implements ClassInstantiatorInterface
{
    /**
     * @var Instantiator
     */
    private $instantiator;

    /**
     * DoctrineInstantiator constructor.
     */
    public function __construct()
    {
        $this->instantiator = new Instantiator();
    }

    public function instantiate(string $className): object
    {
        return $this->instantiate($className);
    }
}
