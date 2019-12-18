<?php


namespace Morebec\Orkestra\ProjectCompilation\Application\Console\Service\FileWatcher;

use InvalidArgumentException;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\OCFile;
use Morebec\ValueObjects\File\Directory;
use Morebec\ValueObjects\File\File;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Responsible for detecting file system changes
 * File created, file modified and file deleted
 * in a directory
 */
class FileWatcher
{
    /**
     * An array containing a cached version of the watched files
     * where the key is the path to a watched, file and the value is
     * its last modified timestamp
     * @var int[]
     */
    private $cacheMap;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Indicates if the file watcher is running or not
     * @var bool
     */
    private $running;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->cacheMap = [];
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Watches a given directory
     * @param Directory $directory
     * @param int $rate refresh rate
     */
    public function watch(Directory $directory, int $rate = 1000)
    {

        // Find files initially without triggering events
        $files = $this->findFiles($directory);
        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $mTime = $file->getMTime();
            $this->cacheMap[$filePath] = $mTime;
        }

        // Watch for changes
        $this->running = true;
        while ($this->running) {
            usleep($rate * 1000);
            $this->findFileChanges($directory);
        }
    }

    public function addSubscriber(EventSubscriberInterface $s)
    {
        $this->eventDispatcher->addSubscriber($s);
    }

    /**
     * Stop File watcher
     */
    public function stop()
    {
        $this->running = false;
    }

    /**
     * @param Directory $directory
     * @return array|Finder
     */
    private function findFiles(Directory $directory)
    {
        if (!$directory->exists()) {
            throw new InvalidArgumentException("Could not watch '$directory', directory does not exist");
        }

        $files = Finder::create()
            ->files()
            ->in((string)$directory)
            ->followLinks()
            ->name('*.' . OCFile::EXTENSION);
        $files = iterator_to_array($files);
        return $files;
    }

    /**
     * @param Directory $directory
     */
    private function findFileChanges(Directory $directory): void
    {
        $files = $this->findFiles($directory);

        /** @var SplFileInfo $file */
        foreach ($files as $file) {
            $filePath = $file->getPathname();
            $fileOb = File::fromStringPath($filePath);
            $mTime = $file->getMTime();

            // Detect New Files and Modified Files
            if (array_key_exists($filePath, $this->cacheMap)) {
                $oldMTime = $this->cacheMap[$filePath];
                if ($mTime === $oldMTime) {
                    continue;
                }
                $this->eventDispatcher->dispatch(new FileModifiedEvent(
                    $fileOb
                ));
            } else {
                // Did not exist
                $this->eventDispatcher->dispatch(new FileAddedEvent($fileOb));
            }

            $this->cacheMap[$filePath] = $mTime;
        }

        // Go Through cache map and detect files that were deleted
        foreach ($this->cacheMap as $filePath => $mTime) {
            $f = File::fromStringPath($filePath);
            if (!$f->exists()) {
                unset($this->cacheMap[$filePath]);
                $this->eventDispatcher->dispatch(new FileDeletedEvent($f));
            }
        }
    }
}
