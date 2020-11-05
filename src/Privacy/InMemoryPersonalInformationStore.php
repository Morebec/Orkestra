<?php

namespace Morebec\Orkestra\Privacy;

/**
 * In Memory Implementation a Personal Information Store.
 */
class InMemoryPersonalInformationStore implements PersonalInformationStoreInterface
{
    /**
     * @var PersonalRecordInterface[]
     */
    private $records;

    public function __construct()
    {
        $this->records = [];
    }

    public function put(PersonalRecordInterface $record): void
    {
        $this->records[$record->getId()] = $record;
    }

    public function findOneByKeyName(string $personalToken, string $keyName): ?PersonalRecordInterface
    {
        foreach ($this->records as $record) {
            if ($record->getPersonalToken() === $personalToken && $record->getKeyName() === $keyName) {
                return $record;
            }
        }

        return null;
    }

    public function findById(string $personalRecordId): ?PersonalRecordInterface
    {
        if (!\array_key_exists($personalRecordId, $this->records)) {
            return null;
        }

        return $this->records[$personalRecordId];
    }

    public function findByPersonalToken(string $personalToken): iterable
    {
        foreach ($this->records as $record) {
            if ($record->getPersonalToken() === $personalToken) {
                yield $record;
            }
        }
    }

    public function remove(string $personalRecordId): void
    {
        if (!\array_key_exists($personalRecordId, $this->records)) {
            return;
        }

        unset($this->records[$personalRecordId]);
    }

    public function erase(string $personalToken): void
    {
        foreach ($this->records as $id => $record) {
            if ($record->getPersonalToken() === $personalToken) {
                unset($this->records[$id]);
            }
        }
    }
}
