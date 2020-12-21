<?php

namespace Morebec\Orkestra\InMemoryAdapter;

use Morebec\Orkestra\DateTime\DateTime;
use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\Scheduling\DomainMessageSchedulerStorageInterface;
use Morebec\Orkestra\Messaging\Scheduling\ScheduledDomainMessageWrapper;
use Morebec\Orkestra\Modeling\TypedCollection;

class InMemoryDomainMessageSchedulerStorage implements DomainMessageSchedulerStorageInterface
{
    /**
     * @var TypedCollection
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new TypedCollection(ScheduledDomainMessageWrapper::class);
    }

    public function add(ScheduledDomainMessageWrapper $wrappedMessage): void
    {
        $this->messages->add($wrappedMessage);
    }

    public function findScheduledBefore(DateTime $dateTime): array
    {
        return $this->messages->filter(static function (ScheduledDomainMessageWrapper $messageWrapper) use ($dateTime) {
            $dt = new DateTime($messageWrapper->getMessageHeaders()->get(DomainMessageHeaders::SCHEDULED_AT));

            return $dt->isBefore($dateTime);
        })->toArray();
    }

    public function findByDateTime(DateTime $from, DateTime $to): array
    {
        return $this->messages->filter(static function (ScheduledDomainMessageWrapper $messageWrapper) use ($from, $to) {
            $dt = new DateTime($messageWrapper->getMessageHeaders()->get(DomainMessageHeaders::SCHEDULED_AT));

            return $dt->isBetween($from, $to, true);
        })->toArray();
    }

    public function remove(ScheduledDomainMessageWrapper $message): void
    {
        $this->messages = $this->messages->filter(static function (ScheduledDomainMessageWrapper $messageWrapper) use ($message) {
            return $messageWrapper->getMessageId() !== $message->getMessageId();
        });
    }
}
