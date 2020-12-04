# Domain Driven Design
Domain driven design is a set of tools used to manage software complexity. 
It provides guidelines to help develop software in a way that is explicit and friendly to change while being deeply
rooted in the business domain problems that an application tries to solve.
In this section we will give a brief explanation of some core concepts of DDD that are recommended by Orkestra.

Domain Driven Design defines two important sets of concepts:
- **Strategic Concepts**: High-level concepts that are used to *design* an application and deepen a development team's
knowledge about the domain for which they need to develop a system.
- **Tactical Concepts**: Are low-level (close to the code) sets of practices that are used to 
put the strategic concepts in the context of actual code.

This section will provide a high-level overview of the different elements that make up these
two categories of concepts.

## Strategic Concepts
### What is a Domain
As its name implies, the word *"Domain"* is very important in DDD. It is at the
core of the strategies it promotes. Though, what is a domain?
Simply put, a domain is the subject area for which an application tries to solve problems.
For example, if we were to develop an application that helps doctors manage and setup appointments 
with their patients, the domain could be "Health Care".

Usually, the exact name of the domain is defined and specified by what we call the *"Domain Experts"*
which are the people that are the experts in the domain for which we develop an application.
In the case of the previous example, the domain experts would be doctors themselves, and we'd need to be in direct contact with them in order to understand their requirements
for the system we'd need to develop.
 
From a programming point of view, people will often refer to **the domain logic** to talk
about the business rules/logic that govern a given domain.
 
### Ubiquitous Language
The next important term in DDD is the "Ubiquitous Language". Although daunting, this
word simply refers the language/jargon used by the Domain Experts when discussing the domain.
For example, when talking about the people they help, doctors will say the word patient.
Lawyers, however, will usually talk about clients.
In turn, the word client has a totally different meaning, when in the context of software development
when we talk about client-server architecture for example.

> **The meaning of a word, is therefore very specific to the domain in which it is being used.**
 
This brings us to another core concept of DDD: context.

### Bounded Context
As we previously saw, a given word from the domain language can have a different 
meaning depending on the context in which it is used.

When the accountants of a business talk about a client, they usually picture something very specific,
that would not correspond to what the Marketing department considers a client.

This all makes sense, the accountants and the marketing department of a company all work together
in the same domain, however they work in different contexts of this domain.

With time, they tend to develop their own internal language.

DDD, advises to identify these contexts within a domain as well as the different meanings a single word
can have. In Domain Driven Design, these contexts are usually referred to as Bounded Context.
This is because they have boundaries out of which concepts can live, but would have very different meanings.

From a coding point of view, this is usually the place where we can identify the different types of modules
that can make up our application. (E.g.: Ordering, Reviews, Billing, Support etc.)

### Model
The final piece we need to go through for the Strategic Concepts, is the Model.
The model is a term that is more known to the development world thanks to patterns like MVC (Model-View-Controller).
It simply means a representation of real world concepts in the form of code that we develop to solve a given set of problems.
In DDD, it is always advised to mirror the domain and the domain language in our models.

For example, in a Health Care system, it would be natural to find classes or services like
*Doctor, Patient, Medication, File, Symptoms etc.*

## Tactical Concepts
Tactical Concepts describes the different strategies that we can use to implement the Strategic Concepts
from a programming point of view. Some developers only rely on the strategic concepts from DDD, while others also 
applies the tactical concepts.

DDD defines a set of building blocks with very specific intents, just like MVC introduces
concepts such as Views or Controllers, Domain-Driven Design focuses on the following concepts:

### Value Objects
Value Objects are one the most interesting concept from tactical DDD.
This is a building block that can also really serve in non DDD projects.

Value objects represents concepts from the domain where the value is important. In other words
two value objects that have the same "value" will always be considered equal. From an OOP perspective
they are implemented using objects of a given class type. They allow to strongly type values instead
of directly relying on builtin types such booleans, floats or string.
They also encapsulate business rules in a single place preventing values from being invalid.

For example, the concept of Money is better represented using a `Money` class rather than a simple float.
As for one, it allows strongly typing methods returning or requiring Money as arguments which makes 
the intent all the more explicit, while also protecting other business rules,
such as cases where an application would need to restrict currencies to a certain list supported.

Another example, could be to have an `EmailAddress` value object instead of a simple string.
Having a class wrap this internal string instead, could prevent invalid email addresses
from being used by the system for example.

This way, developers don't need to know whether a specific string variable contains
a full name, an email address, or a phone number because the type gives context.

This prevent developers from misusing function signatures as this one:
```php
function registerUser(string $fullname, string $emailAddress);
```
Instead, we'd have the following:
 ```php
function registerUser(Fullname $fullname, EmailAddress $emailAddress);
```

In this example, the full name could verify upon construction that the value passed is not
an empty string, forcing full names to always be valid full names.


Another advantage this strategy provides is the possibility for providing helper methods
to use the Value Object:

```php
$hourlyRate = new Money(50.00, new Currency('USD'));
$raise = new Money(5.00, new Currency('USD'));

$newHourlyRate = $hourlyRate->add($raise);
```

One important detail to remember is that Value Objects should be immutable, meaning, in the previous example
the `add` method would return a value object representing the result of this operation, not changing the base salary.
The reason for this is that Value Objects represent values.
Just like the number `1` cannot be changed to something else.
```1 = 2``` would always cause a problem in PHP as a number value is immutable.

### Entities
Contrary to Value Objects, entities are objects for which the identity is of importance. This means that 
two objects of the same type and with the same ID will always be considered equal. Entities should always have 
an attribute called `$id` (that should be implemented as a Value Object) that can be used to test equality:

```php
$userA = new User(new UserId('abc'));
$userB = new User(new UserId('abc'));

$userA->getId()->isEqualTo($userB->getId()); // Would return true
```

Also, contrary to Value Objects, entities are mutable. They hold an internal state, that can change overtime
according to the actions that system take.
It is also considered a best practice to avoid using setters and instead use behavioural methods on these entities:

```php
$patient = $this->patienrRepository->findById(PatientId::fromString('abc'));

// Wrong
$patient->setAddress('01 Health Street', 'USA');

// Good
$patient->relocate(new PatientAddress('01 Health Street', new Country('USA'));
```


### Aggregates & Aggregate Roots
### Factories
### Repositories
### Domain Events
### Domain Services

- **Entity:** An entity represents something that has a list of definite behaviours. The differentiation between entities is done through their IDs (Identifier).
Some examples of common Entities could be `User`, `Adminsitrator` etc.  They are implemented using OOP as regular classes.
- **Value Object:** Value Objects represent concepts. They are mainly implemented using immutable classes. They allow
adding new types that make sense in the context of the domain. For example using a simple integer to represent the age of a user
does not really make sense, as it has internal business rules (such as it cannot be negative). 
- **Domain Event:** represent specific things that have happened in the domain, that has business or importance. in Health Care, `Patient Booked Appointment` would be an important avent. they are usually implemented as simple immutable classes.
- **Aggregate:**
- **Aggregate Root:**
- **Service:**
- **Repository:**
- **Factory:** 