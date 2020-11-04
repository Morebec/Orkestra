<?php

namespace Morebec\Orkestra\EventSourcing\EventStore;

use Throwable;

class StreamNotFoundException extends \RuntimeException implements EventStoreExceptionInterface
{
    /**
     * @var EventStreamIdInterface
     */
    private $streamId;

    public function __construct(EventStreamIdInterface $streamId, $code = 0, Throwable $previous = null)
    {
        $this->streamId = $streamId;
        $message = "Event Store: Stream with ID $streamId not found.";
        parent::__construct($message, $code, $previous);
    }

    public function getStreamId(): EventStreamIdInterface
    {
        return $this->streamId;
    }
}
