<?php

namespace Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\Composer;

use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\NamespaceVO;

/**
 * Corresponds to the configuration values of a composer configuration file.
 */
class ComposerConfiguration
{
    /**
     * @var ComposerNamespaceVO[]
     */
    private $psr4Namespaces;

    public function addPsr4Namespace(ComposerNamespaceVO $param)
    {
        $this->psr4Namespaces[(string)$param->getNamespace()] = $param;
    }

    /**
     * @return NamespaceVO[]
     */
    public function getPsr4Namespaces(): array
    {
        return array_map(
            static function ($n): NamespaceVO {
                /** @var ComposerNamespaceVO $n */
                return $n->getNamespace();
            },
            $this->psr4Namespaces
        );
    }
}
