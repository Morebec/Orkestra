<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher;

use Morebec\ValueObjects\File\File;

class FileDeletedEvent
{
    /**
     * @var File|File
     */
    private $file;

    /**
     * FileDeletedEvent constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @return File
     */
    public function getFile(): File
    {
        return $this->file;
    }
}
