<?php

namespace Morebec\Orkestra\Messaging;

/**
 * Extending Interface of a Domain Message that allows to version them using an integer.
 * By standard definition, everytime a Domain Message has its schema updated, the returned version
 * number of the method @link self::messageVersion} should be bumped to return the previous version + 1.
 * The initial version of a message is always 0 and should never be negative.
 */
interface VersionedDomainMessageInterface extends DomainMessageInterface
{
    /**
     * Returns the version of this domain message's schema.
     */
    public static function getMessageVersion(): int;
}
