# Business documentation
This directory contains the documentation intended for domain experts and developers of the app.
It contains the the definition of all the business related terms so that everyone can be on the same page.
The definitions of all these business terms for the domain at heart, is called the **Ubiquitous language**,
and should be treated as the **source of truth for everything**.

This language is what will **allow developers and domain experts to communicate in a clear way to and prevent
misunderstandings** as much as possible.


**A special note to developers:** The Ubiquitous language **should not include complex technical terms**.
This is not the place to write things like "we shall do POST request against the REST API using JWT Tokens 
so the SPA can get access to our PHP Backend". Keep these implementation details out this documentation, instead
move them to the internal domcumentation. Also the terms found in this documentation should be reflected
**with the same naming system** in the code. For example: If domain experts talk about Coupons with the intended meaning
of discounts, use the word Coupons in the code.


**In the case of Orkestra**, the developers, the end users as well as the domain experts are all, developers. 
Therefore, it makes sense to include a bit more technical terms in this documentation. However, in order to 
keep this as simple, clear and stratighfoward as can be, the implementation detrails, will be kept at a minimum.

## Context
Orkestra is a PHP framework used to speed up the development of Web apps at Morebec by providing
tools to generate code and directory structures, but most of all, to establish a standard for Projects
organization and best practices at large.

Projects using the framework are *usually* based on Symfony, but not restricted to. 
Therefore the framework must work with or without Symfony. 
It will however **always be based on a Composer project or library**.

### Directory Structure
Orkestra *Projects* follow a very specific directory structure, containing 
- A source directory, 
- A documentation directory,
- A tests directory
- A modules directory.

### Layer

#### Domain
#### Application
#### Infrastructure

### Objects
#### Object Essence

## Glossary
- **Composer:** PHP dependency management library.
- **Composer Configuration File**: File containing the composer configuration
- **Composer Correspondsnfiguration**: Corresponds to the configuration values of a composer configuration file.

- **Symfony:** When we refer to symfony, we refer to the version 4 of symfony.

- **Directory:** Do not use the word "folder".

- **OC File:** An orkestra configuration file. They are currently stored as YAML files, but have the .oc extension (stands for Orkestra Configuration), to distinguish them from other unrelated YAML files.

- ***Project*:** An application *Project* using Orkestra and following its philosophy. It is represented by a directory, containing
all the files necessary for the *Project* to work.
***Project* configuration File:** Corresponds to the OC File containing the configuration for the *Project* itself. It is named
`orkestra.oc` and is always located at the root of the *Project*'s directory.
- ***Project* configuration:** Corresponds to configuration of a *Project* and all its configuration values.

- **Code base:** The code base represents all the files contained in a *Project*'s directory.
- **Source code directory:** Directory containing the source code of the Orkestra based App. It is by default called `src` and is located at the root of the *Project*'s *Code base* directory.
(although that can be configured using the *Project* configuration file). 

- **Tests directory:** Directory containing the source code of the tests for the *source code* of the app.
- **Docs directory:** Directory containing the documentation of the *Project*.
- **Internal Documentation**: Documentation intended for developers of the *Project*. Containing implementation details about the *Project*.
- **Business Documentation**: Documentation containing the business terms and requirements.
- **User Documentation**: Documentation intended for the users of the *Project*. In this case the developers that will use Orkestra.

- **Module:** A Module is a grouping of related code around a broad domain concept. E.g.: UserManagement. It is represented by a directory inside the *Project*'s *Source code directory*.
- **Module Directory**: Directory of a module inside the *Source code directory*.
- **Module configuration File:** Corresponds to an *OC File* containing the *Configuration of a Module*, determining where that *Module* is located, how it is named, and the different components it should contain. The file is always named `module.oc`.
- **Module configuration:** Corresponds to configuration of a *Module* and all its configuration values. It is contained in a *Module Configuration File*.
- **Modules Configuration Directory**: Corresponds to the directory that contains the configuration files of all the *Project*'s' *Modules*.

- **Layer:** is a concept representing subdivision of the code inside a *Module*. It is usually represented by a directory inside a *Module's directory*.
- **Layer Configuration:** Configuration of a layer inside a *Module configuration file*.
- **Layer Directory**: Directory containing a layer
- **Layer default subdirectories**: Default set of sub directories inside a layer's directory.


- **Domain Layer**: Corresponds to the concept of the domain layer
- **Infrastructure Layer**: Corresponds to the concept of the infrastructure Layer
- **Application Layer**: Corresponds to the concept of the Application Layer

- **Layer Object:** A LayerObject represents a class, an interface or a trait inside a layer
- **Layer Object Configuration:** Corresponds to the configuration of an object (its schema file, sub directory name etc.)
- **Layer Object Schema File:** File containing the Schema of a Layer Object
- **Layer Object Schema:** Structure of a Value Object (properties, methods etc.)



- **Entity:** Corresponds to the concept of an Entity it is a specific type of Layer Object
- **Entity Configuration**: Corresponds to the configuration of an entity (its schema file, repository file etc.) inside a LayerConfiguration.
- **Entity File:** Corresponds to the PHP file containing the source code of an *Entity* inside a *Module*'s *DomainLaye* containing in the Model/Entity directory.
- **Entity Schema:** Corresponds to the **structure** of an *Entity*, i.e. its methods, properties etc.
- **Entity Schema File:** Corresponds to an *OC File* containing the schema of an *Entity*.

- **Value Object**: Concept of Value Object it is a specific type of Layer Object
- **Value Object Configuration**: Corresponds to the configuration of a Value Object inside a Layer Configuration. 
- **Value Object Schema File**: File containing a Value Object's schema 
- **Value Object Schema**: Structure of a Value Object (properties, methods etc.)

- **Command:** Corresponds to the concept of a *Command*, it is a specific type of Layer Object
- **Command File:** Corresponds to the PHP file containing the source code of a *Command*.
- **Command Schema:** Corresponds to the structure of a *Command*, i.e. its methods, properties etc.
- **Command Schema File:** Corresponds to an *OC File* containing the schema of a *Command*.

- **Command Handler:** Corresponds to the concept of a *Command Handler*, it is a specific type of Layer Object
- **Command File:** Corresponds to the PHP file containing the source code of a *Command Handler*.
- **Command Schema:** Corresponds to the structure of a *Command Handler*, i.e. its methods, properties etc.
- **Command Schema File:** Corresponds to an *OC File* containing the schema of a *Command Handler*.




