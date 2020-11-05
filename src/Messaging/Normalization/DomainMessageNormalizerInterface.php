<?php

namespace Morebec\Orkestra\Messaging\Normalization;

use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Normalization\Denormalizer\DenormalizerInterface;
use Morebec\Orkestra\Normalization\Normalizer\NormalizerInterface;

/**
 * Service responsible for normalizing Domain Messages.
 * It can make use of the DomainMessageClassMapInterface.
 */
interface DomainMessageNormalizerInterface
{
    /**
     * Normalizes a Domain Message.
     */
    public function normalize(DomainMessageInterface $message): ?array;

    /**
     * Denormalizes a Domain Message.
     *
     * @param string|null $messageTypeName an optional domainMessageType name in cases where the type is already known,
     *                                     otherwise it should be detected from the normalized data itself
     */
    public function denormalize(?array $data, ?string $messageTypeName = null): ?DomainMessageInterface;

    /**
     * Adds a normalizer.
     */
    public function addNormalizer(NormalizerInterface $normalizer): void;

    /**
     * Adds a denormalizer.
     */
    public function addDenormalizer(DenormalizerInterface $denormalizer): void;
}
