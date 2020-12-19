<?php

namespace Morebec\Orkestra\Messaging;

use Morebec\Orkestra\Messaging\Routing\HandleDomainMessageMiddleware;
use Morebec\Orkestra\Messaging\Routing\RouteDomainMessageMiddleware;

/**
 * Domain Message headers represent metadata about a message when going through the domain message bus.
 * The data contained in these headers should always be primitive scalar types and null with the exception of array
 * that can be stored, as long as it contains only the aforementioned value types.
 */
class DomainMessageHeaders
{
    /**
     * Key in the headers representing the correlation ID.
     * The correlation ID is used to track the initial message that was responsible for this message to be sent at
     * a given point.
     * Expected Value: string.
     *
     * @var string
     */
    public const CORRELATION_ID = 'correlationId';

    /**
     * Key in the headers representing the causation ID.
     * The causation ID is used to track the message that caused this message to be sent.
     * By usually following the chain of causation ids of a given message we land on the correlation message.
     * Expected Value: string.
     *
     * @var string
     */
    public const CAUSATION_ID = 'causationId';

    /**
     * Key in the headers representing the ID of the message.
     * Expected Value: string.
     *
     * @var string
     */
    public const MESSAGE_ID = 'messageId';

    /**
     * Key in the headers representing the type name of the message.
     * Expected Value: string.
     *
     * @var string
     */
    public const MESSAGE_TYPE_NAME = 'messageTypeName';

    /**
     * Key in the headers representing the type of the message. (e.g. Command, Event, Query, Generic).
     *
     * @var string
     *             Expected Value: string
     */
    public const MESSAGE_TYPE = 'messageType';

    /**
     * Key in the headers representing the destination handler where this message should be sent.
     * This header is optional and can support a null value (null or empty array) or an array.
     * In that case, the message will be sent to all subscribed handlers.
     * Otherwise it can be used to force specific handlers to receive a given message.
     * Expected Value: string[].
     *
     * Each string should be as follows:
     * - handlerClassName::methodName.
     *
     * This is used by the {@link HandleDomainMessageMiddleware} and the {@link RouteDomainMessageMiddleware}
     *
     * @var string
     */
    public const DESTINATION_HANDLER_NAMES = 'destinationHandlerNames';

    /**
     * Key in the headers representing the datetime at which this message was sent.
     * Expected Value: milliseconds precise timestamp.
     *
     * @var string
     */
    public const SENT_AT = 'sentAt';

    /**
     * Key in the headers representing the datetime at which this message is scheduled to be sent.
     * Expected Value: milliseconds precise timestamp|null.
     *
     * @var string
     */
    public const SCHEDULED_AT = 'scheduledAt';

    /**
     * Key in the headers representing the ID of a tenant to which this message is directed.
     * Expected Value: string|null.
     *
     * @var string
     */
    public const TENANT_ID = 'tenantId';

    /**
     * @var array
     */
    private $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * Adds a Header information.
     *
     * @param $value
     */
    public function set(string $key, $value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * Returns the value of a key, or a default value.
     *
     * @param null $defaultValue
     *
     * @return mixed
     */
    public function get(string $key, $defaultValue = null)
    {
        if (!$this->has($key)) {
            return $defaultValue;
        }

        return $this->values[$key];
    }

    /**
     * Indicates if a given key is present.
     */
    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->values);
    }

    /**
     * Represents an array representation of the headers.
     */
    public function toArray(): array
    {
        return $this->values;
    }
}
