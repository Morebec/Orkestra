<?php

namespace Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher;

use Morebec\ValueObjects\File\File;

/**
 * Fired when the watcher has detected that a file was modified
 */
class FileModifiedEvent extends FilesystemEvent
{
    /**
     * @var File
     */
    private $file;

    /**
     * FileModifiedEvent constructor.
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
