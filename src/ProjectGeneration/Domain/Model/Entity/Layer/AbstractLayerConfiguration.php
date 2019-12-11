<?php


namespace Morebec\Orkestra\ProjectGeneration\Domain\Model\Entity\Layer;

use Morebec\ValueObjects\Text\Description;

class AbstractLayerConfiguration
{

    const DESCRIPTION_KEY = 'desc';

    /**
     * @var string[]
     */
    private $subDirectoryNames;

    /**
     * @var Description|null
     */
    private $description;
    /**
     * @var string
     */
    private $name;

    /**
     * AbstractLayerConfiguration constructor.
     * @param string[] $subDirectoryNames
     * @param Description|null $description
     */
    public function __construct(string $name, array $subDirectoryNames, ?Description $description)
    {
        $this->subDirectoryNames = $subDirectoryNames;
        $this->description = $description;
        $this->name = $name;
    }

    /**
     * @return string[]
     */
    public function getSubDirectoryNames(): array
    {
        return $this->subDirectoryNames;
    }

    /**
     * @return Description|null
     */
    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function getName(): string
    {
        return $this->name;
    }
}