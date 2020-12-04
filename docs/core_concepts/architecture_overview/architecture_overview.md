# Architecture Overview
Orkestra is built in a way that allows applications based on it to easily follow the architectural principles of
Domain-Driven Design (a.k.a DDD), Event-Driven Programming, Command Query Responsibility Segregation (a.k.a CQRS) and Event Sourcing (a.k.a ES).
Implementing these principles in an application usually results in more robust applications that are aligned with the business needs
of the clients and their customers rather than with technical concerns. For these reasons Orkestra does not force technological decisions
upon the applications using it (apart from being PHP based), but rather provides the building blocks for supporting these conceptual ideas and patterns.

These concepts in order to support resiliency usually require a lot of plumbing, that can be demanding to code from scratch on every project.
Orkestra was born out of necessity after working on a wide range of applications all facing similar common challenges.

> It is advised to get acquainted with these Architectural and Conceptual Patterns before reading more. 
> This documentation assumes that the reader is at least somewhat familiar with the underlying concepts.
> Since explaining these concepts is beyond the scope of this documentation and might require further research on your part, 
> you can still read our introductory pages on each of these patterns:
>    - [Domain Driven Design](domain-driven-design.md)
>    - [Command Query Responsibility Segregation](cqrs.md)
>    - [Event-Driven Programming](event-driven-programming.md)
>    - [Event Sourcing](event-sourcing.md)

## Inherent Decoupling
All these architectural patterns have one thing in common: They all try to achieve high decoupling as much as possible.
Indeed, in order for a system to evolve gracefully over time, it needs to be coded in a way that makes its internal
units as independent of one another as possible.
 
- **Domain Driven Design** tries to achieve that by the use of bounded contexts that serve as responsibility and business language boundaries 
- and aggregate roots that serve as transactional boundaries.
- **Command Query Responsibility Segregation** aims to achieve decoupling by separating the requirements for reading and writing information into two distinct sides of an application.
- **Event-Driven Programming** aims to achieve this by allowing different components and systems to interact with one another using messaging contracts.
- **Event Sourcing** aims to achieve this goal by separating the concepts of time, state and change.

Being conceptual patterns, they all achieve this goal by using a common means: contracts.

As such, Orkestra aims to achieve the same goal by providing interface contracts.

## Messaging Patterns
Orkestra provides tools to use messages as a means of communication between different components of an application.
This means that for two components to communicate, instead of directly calling each other, thus creating a strong coupling
between the two, messages are used and published internally.
This proposes several advantages:
- Message publishers do not need to keep track of all the other components interested in a given message.
- Messages become first class citizen of the source code allowing them to be logged, traced or stored for later processing.
- Provides the possibility to easily distribute these messages to other remote systems, if required.

Although not mandatory, Orkestra considers messaging as a core concept.


##