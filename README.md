# Orkestra
Orkestra is an opinionated framework with a plethora of recommendations on architectural design
that we use internally at Morebec to develop new products. It also provides technical tools to quickly create
products that are easy to maintain and scale.

Therefore, we define two types of tools that the Orkestra framework provides:
- **Conceptual tools** represent a set of some best practices and recommendations on how to structure an application.
- **Technical tools** represents a coding library providing components to adhere to the conceptual tools. 

At its core, Orkestra provides utilities for DDD, CQRS and Event Sourcing.

Orkestra allows to quickly develop products and applications while maintaining
a standardized approach that is easy to understand and improve.

## Why Orkestra
Given the current nature of Morebec, which is indie software development, in order to provide value
to our clients, we need to be able to have a structure that is similar from project to project, while offering
a stable platform that can stand the test of time and scale.

Building applications that will be public facing on production is a challenge in itself that requires
a lot of thinking and preparation.

To ensure that we can always provide the same level of quality, while being prepared for the potential scale
of our clients, Orkestra serves as both a technical and thinking framework
to help us achieve these goals.


From a technical stand point, Orkestra tries to be as unobtrusive as possible by relying mostly
on interfaces that denote the conceptual contracts it tries to fulfill. 
Scalable and resilient CQRS and Event Sourcing requires a lot of plumbing that is very easy to get wrong, 
as such it provides default implementation of these contracts to stay as much as possible at the edges of the
different layers (application, domain and infrastructure.)


- Domain Driven Design
- Command Query Responsibility Segregation
- Event Sourcing