<?php

namespace Morebec\Orkestra\Messaging\Normalization;

use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Normalization\Denormalizer\DenormalizerInterface;
use Morebec\Orkestra\Normalization\Normalizer\NormalizerInterface;
use Morebec\Orkestra\Normalization\ObjectNormalizer;

class DomainMessageNormalizer implements DomainMessageNormalizerInterface
{
    /**
     * @var ObjectNormalizer
     */
    private $normalizer;
    /**
     * @var DomainMessageClassMapInterface
     */
    private $domainMessageClassMap;

    public function __construct(DomainMessageClassMapInterface $domainMessageClassMap)
    {
        $this->normalizer = new ObjectNormalizer();
        $this->domainMessageClassMap = $domainMessageClassMap;
    }

    public function normalize(DomainMessageInterface $message): ?array
    {
        $data = $this->normalizer->normalize($message);
        $data['messageTypeName'] = $message::getTypeName();

        return $data;
    }

    public function denormalize(?array $data, ?string $domainMessageType = null): ?DomainMessageInterface
    {
        $domainMessageType = $domainMessageType ?: $data['messageTypeName'];

        $className = $this->domainMessageClassMap->getClassNameForDomainMessageTypeName($domainMessageType);
        if (!$className) {
            throw new \InvalidArgumentException(sprintf('Could not find a Class Name for Domain Message "%s". Did you add it to the DomainMessageClassMapInterface?', $domainMessageType));
        }

        return $this->normalizer->denormalize($data, $className);
    }

    public function addNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer->addNormalizer($normalizer);
    }

    public function addDenormalizer(DenormalizerInterface $denormalizer): void
    {
        $this->normalizer->addDenormalizer($denormalizer);
    }
}
