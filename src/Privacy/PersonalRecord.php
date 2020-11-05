<?php

namespace Morebec\Orkestra\Privacy;

use Morebec\Orkestra\DateTime\DateTime;

/**
 * Represents the recording of PII for a given person.
 * Note: The term business identification relates to any internal identifier that is used
 * to standardize and describe a given process.
 * E.g.:
 * MARKETING_PROCESSING: Personally Identifiable Information that is stored under these terms are processed
 * in order to allow our marketing department to do their standard operations. It will be stored in a computer owned by the organization
 * as well as a AWS S3 storage. It will be stored and encrypted using these algorithm.
 * These could also refer more categories of lawful basis such as
 * - user_consent,
 * - contract,
 * - legal_requirement
 * - legitimate_interest
 * - vital_interest
 * - public_interest.
 *
 * There can be cases where an application will be saving PII that was entered by a given user
 * concerning other data subjects. (E.g. an application serving as a CRM for its users.).
 * In these cases, practical use of the personal record should include additional metadata
 * to handle these situations.
 * One strategy could be to use the source field by applying the the GUID of the user that collected this information.
 *
 * Concerning metadata, we advise saving data such as
 * - Processing Operations
 * - Agreements - (user consent)
 * - Legal basis
 * - Sharing with 3rd parties.
 */
class PersonalRecord implements PersonalRecordInterface
{
    /**
     * personal record Id.
     *
     * @var string
     */
    public $id;

    /** @var string */
    public $personalToken;

    /**
     * A business identification of a mean by which the personal information of a person or data subject was collected:
     * E.g.: A Product's Landing Page Contact Form, an External Organization etc.
     *
     * @var string
     */
    public $source;

    /**
     * A list of business identifications of reasons why this information is collected by the operating business.
     * (E.g. Marketing, CRM, Processing/Analytics).
     *
     * @var string[]
     */
    public $reasons = [];

    /**
     * A business identification of the ways in which this information is going to be processed.
     *
     * @var string[]
     */
    public $processingRequirements = [];

    /**
     * This can be used when required to query a record.
     *
     * @var string name of the type of value that is saved (e.g. emailAddress, phoneNumber, IP Address).
     */
    public $keyName;

    /** @var mixed value of the PII */
    public $value;

    /**
     * Date Time at which this information should be considered no longer needed and be automatically deleted.
     * If this value is null it is considered that it should be erased upon of request or other manual events.
     *
     * @var DateTime|null
     */
    public $disposedAt;

    /**
     * This metadata should be used to track additional information related to this record
     * the 3rd parties involved with this PII that should be notified upon breaches
     * or invocation of the right to erasure.
     *
     * @var mixed[]
     */
    public $metadata = [];

    /**
     * @var DateTime|null
     */
    public $collectedAt;

    public function __construct(string $id, string $personalToken, string $keyName, $value, string $source)
    {
        $this->id = $id;
        $this->personalToken = $personalToken;
        $this->keyName = $keyName;
        $this->value = $value;
        $this->source = $source;
        $this->disposedAt = null;
        $this->collectedAt = null;
        $this->reasons = [];
        $this->processingRequirements = [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getPersonalToken(): string
    {
        return $this->personalToken;
    }

    public function getKeyName(): string
    {
        return $this->keyName;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }

    public function getProcessingRequirements(): array
    {
        return $this->processingRequirements;
    }

    public function getDisposedAt(): ?DateTime
    {
        return $this->disposedAt;
    }

    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getCollectedAt(): ?DateTime
    {
        return $this->collectedAt;
    }

    public function getValue()
    {
        return $this->value;
    }
}
