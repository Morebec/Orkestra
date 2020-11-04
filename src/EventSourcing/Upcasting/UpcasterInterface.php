<?php

namespace Morebec\Orkestra\EventSourcing\Upcasting;

/**
 * An Upcaster is responsible for modifying a domain message's normalized data before denormalization
 * so that previously stored data can be made compatible with the current schema of the Typed Message.
 * This is done on the normalized form of a message in order to be able to denormalize it to a specific type,
 * not requiring the code to support all other previous schema versions of a given message.
 * Upcasting applies mostly to Event when saved in the event store, but could be used for other purposes as well.
 */
interface UpcasterInterface
{
    /**
     * Upcasts a domain message's normalized form to another form and returns it as an array of messages.
     * It returns an array of upcasted messages to give better control to the upcaster in the processing of messages allowing a given
     * message to be upcasted into multiple events (demultiplexing) or entirely skipped. The messages should always
     * be in their normalized form, to allow deferring denormalization to a later stage.
     *
     * @param UpcastableMessage $message the data
     *
     * @return UpcastableMessage[] zero, one or multiple upcasted normalized messages
     */
    public function upcast(UpcastableMessage $message): array;

    /**
     * Indicates if this Upcaster supports a given message and data schema.
     */
    public function supports(UpcastableMessage $message): bool;
}
