# Command Query Responsibility Segregation
CQRS is an architecture style in which there are two different models for reading and writing data.
CQRS argues that the shape of the model required to write something in a database is more often than
not, very different to the model needed for reading or displaying that data.
The basic idea, is that these two operations are categorised as follows:
- **Commands**: Represent operations that **change** the state of the system (write).
- **Queries**: Operations that do not change the state of the system and simply **reads** and returns the requested data.

 