# Messaging
At the core of Orkestra is the domain and its **Messaging mechanism**.
In Orkestra based applications, the communication between different components is made through Messaging, i.e. using **Domain Message** objects.
This allows to have a decoupled system, as well as providing extensibility in how these messages travel from one point to another when needed.

In essence this messaging mechanism means that the flow of a program is as follows:
- A `Message Producer`  will produce a `Message` that will be sent, and handled by a `Message Handler`.

This is somewhat similar to the HTTP protocol where a Client (Message Producer)  sends a Request (Message) to a server  (Message Handler) that handles it 
and returns a response indicating how the handling of the request went.

For people unfamiliar with this type of programming, this can look
 overly complex at first. However, it provides powerful possibilities. Here are some:
- Message publishers do not need to keep track of all the other components interested in a given message.
- Messages become first class citizen of the source code allowing them to be logged, traced or stored for later processing (asynchronous processing).
- Provides the possibility to easily distribute these messages to other remote systems, if required.

Orkestra provides a few interfaces for these concepts:
- `DomainMessageInterface` used to indicate that a given class is a domain messages.
- `DomainMessageHandlerInterface` used to indicate that a given class is a message handler.

> There is no interface for the Message Producers as a Message can be produced by a lot of different components in an application
> and does not require any specific differentiation or indicators.
> The important pieces of that contract are the messages themselves and their handlers.

### Types of Messages
Orkestra defines three types of messages out of the box that all extend the base `DomainMessageInterface`:
- `DomainCommandInterface` represents a Command in a CQRS sense. 
- `DomainEventInterface` represents an Event in an Event-Driven Programming/Event Sourcing sense
- `DomainQueryInterface` represents a Query in a CQRS sense.

These messages have very specific intents and meaning and cannot be used interchangeably.
From a conceptual point of view, a message is immutable and cannot be changed once created.
Indeed, this is because they represent specific intents or facts.
Just like a postman cannot start opening letters and changing their contents, messages are
immutable things. 

#### Domain Commands
Domain Commands represent a desire or intent to do something in the system. (e.g. Registering a user account, activating/deactivating it etc).
 They can be implemented using the `DomainCommandInterface` that inherits the `DomainMessageInterface`.

> Best Practice: Only store primitive values in Commands for serialization purposes, also consider Commands as Immutable.

Domain Commands are always sent to a single destination which is a Domain Command Handler (implementing the `DomainCommandHandlerInterface`).

#### Domain Queries
Queries represent a request for information on something. They can be implemented using the `DomainQueryInterface` that inherits the `DomainMessageInterface`.

> Best Practice: Only store primitive values in Queries for serialization purposes, also consider Queries as Immutable.

Queries are always sent to a single destination which is a Query Handler (implementing the `DomainQueryHandlerInterface`).]

#### Domain Events
Domain Events are a very different type of message. They conceptually represent things that happened in the past and are immutable facts.
They can be implemented using the `DomainEventInterface` that inherits the `DomainMessageInterface`.

> Best Practice: Only store primitive values in Events for serialization purposes, also since they represent things that already happened, 
> consider Events as Immutable.

In terms of messaging, they can get sent to **multiple locations** (in contrast to Commands and Queries) depending on the processes in place.
For example, they can be stored in a database or a file storage, sent over the network etc.

They are handled Asynchronously (since events are after the fact occurrences) by Domain Event Handlers implementing the `DomainEventHandlerInterface`.

### Domain Message Bus
Messages are good, but we need a way to send these messages to their interested message handlers.
One way could be to directly call the message handler:

```php
function controllerAction(Request $request) {
    $registerAccountCommand = new RegisterAccountCommand (
        $request->post->get('emailAddress'),
        $request->post->get('password')
    );
    $this->registerAccountCommandHandler->handle($registerAccountCommand);
}
```

This can be effective for very small pet projects, however it presents important shortcomings:
- If multiple parts of the system need to register a user account, 
they would need to have access to the handler in all those places.
- If we ever want to add logging before and after processing a given command, we'd need to manually add all that code
everywhere this communication takes place.
- There is no easy way to reroute the command to a more specialized handler such as a `CustomerSpecificAccountRegistrationHandler`,
unless we bloat our code with `if` statements, again in all the places where this communication happens. 

A better option is to use the Mediator Pattern, which essentially prescribe hiding the actual
communication of a message to a handler behind a dedicated service.

In Orkestra, this is the role of the `DomainMessageBusInterface`. The role of this interface is
to effectively route a message from its producer to its handler(s), while providing ways to hook into
this routing process, in order to do custom work before and after a message is handled.
It also returns a response, (called a Domain Response), that serves as a form of acknowledgement or result of operation.


In order to allow this the interface prescribes the use of `DomainMessageBusMiddlewareInterface` which should be
services that can hook into this process by performing tasks before and after a message is handled.

A Default `DomainMessageBus` implement is available out of the box.

These message sending operations can be nested depending on the operational flow of the domain. (E.g. Command => Command Handler => Event => Event Handler).

THe `DomainMessageBusInterface` also provides a way to send metadata along with the message to possibly alter the behaviour
of the message bus and the handlers. This is done using the `DomainMessageHeaders` object.

#### Domain Message Router
The Domain Message Router is a service responsible for keeping a registry of the routes
where a given message can be sent. It is used by the Domain Message Bus for it to determine where to send a given message.

#### Domain Message Interception: Domain Message Bus Middleware
Sometimes depending on the needs of the application, it might be required to intercept, filter or simply eavesdrop on a message.
Such cases can be useful for *authentication*, *authorization*, or *logging purposes* as a few examples.

This can be achieved with the use of Middleware.
Middleware are pieces of logic that can hook into the sending process of the message bus by performing work before and after 
a message is sent and handled by a Domain Message Handler.

Here's an example of a middleware that logs the type of messages being sent:
```php
class LoggerMessageBusMiddleware implements DomainMessageBusMiddlewareInterface
{
    /** @var LoggerInterface */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(DomainMessageInterface $message, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        $messageId = $headers->get(DomainMessageHeaders::MESSAGE_ID);

        $this->logger->info('[Domain Message Bus] Sending message "{messageType}" ...', [
            'messageId' => $messageId,
            'messageType' => $message::getTypeName()
        ]);

        $response = $next($message, $headers);

        $this->logger->info('[Domain Message Bus] Message Sent "{messageType}".', [
            'messageId' => $messageId,
            'messageType' => $message::getTypeName()
        ]);

        return $response;
    }
}
```

#### Domain Message Handling
Messages are handled by Handlers. Since we define by default three types of messages, there are three types of message handlers:
- Command Handlers -> Handle Command messages to perform changes in the domain and implement the `DomainCommandHandlerInterface`
- Event Handlers -> Handle Event messages to trigger side effects and implement the `DomainEventHandlerInterface`
- Query Handlers -> Handle Query messages to return information and implement the `DomainQueryHandlerInterface`


#### Synchronous Handling vs Asynchronous Handling
By default, the domain message bus sends domain messages to their handlers synchronously.
However, depending on the type of message or specific circumstances, it might be required to asynchronously handle a message.

In order to do this Orkestra provides a `DomainMessageSchedulerInterface` service, that is responsible for scheduling
messages to be sent back to the bus at a given date and time for synchronous processing.
To easily schedule messages, the Default Implementation of the Domain Message Bus contains a Middleware capable of
automatically scheduling messages when they have a specific header:
```php

$domainMessageBus->sendMessage($message, new DomainMessageHeaders([
    DomainMessageHeaders::SCHEDULED_AT => $clock->getTimestampMillis()
]));
``` 

In another process a watcher can be used to poll the `ScheduledDomainMessageStorageInterface` for messages 
ready to be sent and send them back on the bus.

> Best Practice: Although it is technically possible to schedule any type of message, it is recommended not doing so, 
> as it is a functionality intended mostly for Commands. Queries should return results, scheduling a query will return no result in the current process. 
> Events are things that happened in the past, if we schedule an Event it essentially equals to predicting the future (which is not something that makes sense).
> Concerning Events, it could also lead to inconsistencies since they are intended to be saved in an Event Store.
> Unless you are not saving events and opt for a purely Event-Driven Architecture, the use cases for scheduling events should be extremely rare. 
> While scheduling a message might have its uses from time to time, it is advised to use **Process Managers** to have greater control on retry strategies 
> or graceful failure handling and the like.


### Sending & Processing Domain Events.
Depending on the implementation you use, that is storing Events in an `Event Store` or not, this process will be very different.

The `Domain Message Bus` is always responsible for routing a given message type to its corresponding message handler for **Synchronous** processing.
This means that optimally, this Synchronous Processing should be performed after events are stored, in a different machine process.
By default, Orkestra handles this by providing a type of service called the `Event Processor` that is responsible for subscribing to the 
`Event Store` and sending events synchronously for processing through the `Domain Message Bus` whenever new events are available using a Worker process. 

> Note: This implementation relying on an Event Store, avoids two-phase commits between the `Event Store` and the sending of the message to the `Domain Message Bus`. 
> By subscribing to the event store and continuously checking if new messages should be handled, enables the processor to be able to resume from where it stopped
>if it ever fails or shuts down.

However, if you are not using the `Event Store` whatsoever, you can simply implement this as you like, by either processing the events Sycnhronously in the same
domain transaction (and machine process) or by implementing your own `Event Bus` capable of dispatching events.

> Best Practice: Relying on both the `Event Store` and an in-process `Domain Message Bus` is discouraged as it requires a lot of technical requirements.
(Such as message order resiliency or two phase-commits). a Pub-Sub implementation based on the book of records allows getting rid of those 
> two technical challenges. (Although one solution for out-of order messages is to poll
> the source of truth upon notification of a new message to determine if this message is out of order.)


#### Handling Exceptions
By design the `Domain Message Bus` and `Domain Message Scheduler` are expected not to throw exceptions related to the handling of a message.
As part of their contract they must always return a `domain response` indicating success or failure. 
The default implementation provides a set of Status Codes that allow the event producers to easily distinguish between 
Domain Exceptions (Business specific exceptions) and Technical Errors.
Allowing the Application Layer to better handle these failures with greater granularity.
It also provides the benefit of not disrupting the execution flow of the `Domain Message Bus` when different message sending operations are nested. 
As an example, one Event Handler failing should not prevent other event handlers of doing their work if they are called as part of the same domain context.

As such, exceptions should always be swallowed by these components so that they can return a response indicating those exceptions.

This is achieved with middleware.

This allows to freely use exceptions in message handlers so that doing that will only interrupt the current handler's scope.