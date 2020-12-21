<?php

namespace Morebec\Orkestra\InMemoryAdapter;

use Morebec\Orkestra\Modeling\TypedCollection;
use Morebec\Orkestra\Privacy\PersonalInformationStoreInterface;
use Morebec\Orkestra\Privacy\PersonalRecordInterface;

class InMemoryPersonalInformationStore implements PersonalInformationStoreInterface
{
    /**
     * @var TypedCollection
     */
    private $data;

    public function __construct()
    {
        $this->data = new TypedCollection(PersonalRecordInterface::class);
    }

    public function put(PersonalRecordInterface $record): void
    {
        $this->data->add($record);
    }

    public function findOneByKeyName(string $personalToken, string $keyName): ?PersonalRecordInterface
    {
        return $this->data->findFirstOrDefault(static function (PersonalRecordInterface $record) use ($personalToken, $keyName) {
            return $record->getKeyName() === $keyName && $record->getPersonalToken() === $personalToken;
        });
    }

    public function findById(string $personalRecordId): ?PersonalRecordInterface
    {
        return $this->data->findFirstOrDefault(static function (PersonalRecordInterface $record) use ($personalRecordId) {
            return $record->getId() === $personalRecordId;
        });
    }

    public function findByPersonalToken(string $personalToken): iterable
    {
        return $this->data->filter(static function (PersonalRecordInterface $record) use ($personalToken) {
            return $record->getPersonalToken() === $personalToken;
        });
    }

    public function remove(string $personalRecordId): void
    {
        $this->data = $this->data->filter(static function (PersonalRecordInterface $record) use ($personalRecordId) {
            return $record->getId() !== $personalRecordId;
        });
    }

    public function erase(string $personalToken): void
    {
        $this->data = $this->data->filter(static function (PersonalRecordInterface $record) use ($personalToken) {
            return $record->getPersonalToken() !== $personalToken;
        });
    }
}
