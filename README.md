# Orkestra
Orkestra is an opinionated framework with a plethora of recommendations on architectural design
that we use internally at Morebec to develop new products. It also provides technical tools to quickly create
products that are easy to maintain and scale.

At its core, Orkestra provides utilities for DDD, CQRS and Event Sourcing.

Orkestra allows to quickly develop products and applications while maintaining
a standardized approach that is easy to understand and improve.

## Why Orkestra
Given the current nature of Morébec, which is indie software development, in order to provide value
to our clients, we need to be able to have a structure that is similar from project to project, while offering
a stable platform that can stand the test of time and scale.

Building applications that will be public facing in production is a challenge in itself that requires
a lot of thinking and preparation.

To ensure that we can always provide the same level of quality, while being prepared for the potential scale
of our clients, Orkestra serves as both a technical and thinking framework
to help us achieve these goals.


From a technical stand point, Orkestra tries to be as unobtrusive as possible by relying mostly
on interfaces that denote the conceptual contracts it tries to fulfill. 
Scalable and resilient CQRS and Event Sourcing requires a lot of plumbing that is very easy to get wrong, 
as such it provides default implementation of these contracts to stay as much as possible at the edges of the
different layers (application, domain and infrastructure.)
This set of interfaces allows different projects to extend the framework with 
their own implementations when needed while still remaining compatible with the Orkestra framework and 
its ecosystem.

## Features
- Domain Driven Design Building Blocks.
- Command Query Responsibility Segregation (CQRS) building blocks.
- Event Sourcing building blocks.
- Intra Application Messaging.
    - Based on Chain of responsibility pattern (middleware pipeline)
    - Synchronous processing by default. 
    - Asynchronous processing using workers and scheduling. 
    - Scheduling support.
    - Tracing with correlation and causation tracking.
- Explicit Personal Information Storage Interface.

## Installation
To include Orkestra in your projects, it is highly recommended relying on `composer`.

Run the following composer command:
```shell script
$ composer require `morebec/orkestra`
```

### Adapters
Orkestra relies on adapters to add functionality to the base classes.
- [OrkestraSymfonyBundle](https://github.com/Morebec/OrkestraSymfonyBundle) - Integrates Orkestra with Symfony
- [OrkestraMongoDbAdapter](https://github.com/Morebec/OrkestraMongoDbAdatper) - Add Mongo DB storage support for Orkestra services 

## Usage && Documentation
For the documentation on how to use Orkestra and its core principles, please refer to the `docs/` directory.
