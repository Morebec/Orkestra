# Usage


## Commands
### How to create a Command
### How to create a CommandHandler
### How to create a CommandValidator
### How to dispatch and execute a Command
#### Using a Module
#### Using direct access to a command bus

## Events
### How to create an Event
### How to create an EventHandler
#### Using a Module
#### Using direct access to an event bus

## Queries
### How to create a Query
### How to create a QueryHandler
#### How to create a QueryValidator
### How to execute a Query
#### Using a Module
#### Using direct access to an query bus

## Repositories
### How to create a Repository
### How to implement a Repository
#### Using an ORM
### How to execute a query
#### Using a Module
#### Using direct access to a query

## Notifications
### How to create a Notification
### How to create a NotificationHandler
#### How to send a Notification
##### Using a Module
##### Using direct access to a notification bus

### Domain Exceptions

## Best Practices
### Layered Architecture
#### Application Layer
#### Domain Layer
#### Infrastructure Layer
#### Anticorruption layers
### Write Model
### Read Model
### Value Objects where to use
Not in commands, or events.
In write model use Write Specific Value Objects
In Read Model use Read Specific Value Objects
#### Querying: When to Query the Read Model and the Write Model
#### Using direct access to messaging buses vs a Module
#### Internal module interaction vs external interaction