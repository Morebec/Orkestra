<?php

namespace Morebec\Orkestra\Modeling;

/**
 * Interface representing an Aggregate Root.
 * It is used as a marker interface to indicate that a given model class is an aggregate root.
 * Aggregate roots are an isolated tree of entities.
 * They are responsible for managing their child entities and provide a public interface to them.
 * As such these nested entities are considered private to this aggregate root and cannot be changed from
 * outside an aggregate root's public API.
 */
interface AggregateRootInterface
{
}
