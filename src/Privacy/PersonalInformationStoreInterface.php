<?php

namespace Morebec\Orkestra\Privacy;

/**
 * Represents a Storage that can be used to contain all Personally Identifiable Information
 * that the application tracks. To provide an easy way to maintain and get rid of it when required.
 * The use of this storage should be an explicit dependency in all {@link DomainMessageHandler} and `Domain Services`,
 * when Personally identifiable information is stored as Privacy Data Compliance should be an explicit business requirement.
 *
 * This storage interface works with some core concepts:
 * - A Personal Record represents a single PII value such as a person's name, email address, phone number, birthdate etc.
 * - A Personal Token - represents a token that is used internally by the application to identify a given person. Such as an internal UUID.
 * - A Personal Record Token - Represents a reference to a given Personal Record, that can be used in the application to reference data contained there.
 *
 * To ensure an application is still able to work when the information is deleted, once a personal record is created and linked to a personal
 * record token, querying this personal record token will allow to return a default value instead (such as unavailable).
 */
interface PersonalInformationStoreInterface
{
    /**
     * Allows to put a Personal Record to this store, or overwrite an already existing one.
     */
    public function put(PersonalRecordInterface $record): void;

    /**
     * Finds one personal records by its key name and personal token combination or null if it was not found (never put).
     */
    public function findOneByKeyName(string $personalToken, string $keyName): ?PersonalRecordInterface;

    /**
     * Finds one personal record by its ID.
     */
    public function findById(string $personalRecordId): ?PersonalRecordInterface;

    /**
     * Finds records by personal tokens.
     *
     * @return array
     */
    public function findByPersonalToken(string $personalToken): iterable;

    /**
     * Removes a given record by its personal record id.
     */
    public function remove(string $personalRecordId): void;

    /**
     * Erase all personal records linked to a personal token.
     */
    public function erase(string $personalToken): void;
}
