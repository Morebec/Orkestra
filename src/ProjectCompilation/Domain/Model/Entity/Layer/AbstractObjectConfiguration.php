<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Layer;


class AbstractObjectConfiguration
{
    /**
     * @var AbstractObjectSchema
     */
    private $schema;

    /**
     * Sub Directory name
     * @var string
     */
    private $subDir;
}