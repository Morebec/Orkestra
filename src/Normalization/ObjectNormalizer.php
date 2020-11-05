<?php

namespace Morebec\Orkestra\Normalization;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;
use Morebec\Orkestra\Normalization\Denormalizer\DenormalizationContext;
use Morebec\Orkestra\Normalization\Denormalizer\DenormalizationContextInterface;
use Morebec\Orkestra\Normalization\Denormalizer\Denormalizer;
use Morebec\Orkestra\Normalization\Denormalizer\DenormalizerInterface;
use Morebec\Orkestra\Normalization\Denormalizer\ObjectDenormalizer\FluentDenormalizer;
use Morebec\Orkestra\Normalization\Normalizer\NormalizationContext;
use Morebec\Orkestra\Normalization\Normalizer\NormalizationContextInterface;
use Morebec\Orkestra\Normalization\Normalizer\Normalizer;
use Morebec\Orkestra\Normalization\Normalizer\NormalizerInterface;
use Morebec\Orkestra\Normalization\Normalizer\ObjectNormalizer\FluentNormalizer;

/**
 * The Object Normalizer is capable of normalizing and denormalizing objects.
 */
class ObjectNormalizer implements ObjectNormalizerInterface
{
    /**
     * @var Normalizer
     */
    private $normalizer;

    /**
     * @var Denormalizer
     */
    private $denormalizer;

    public function __construct()
    {
        $this->normalizer = new Normalizer();
        $this->denormalizer = new Denormalizer();

        // Messaging Normalization Requirements
        $this->addNormalizer(FluentNormalizer::for(DomainResponseStatusCode::class)->asString());
        $this->addDenormalizer(
            FluentDenormalizer::for(DomainResponseStatusCode::class)
                ->as(static function (DenormalizationContextInterface $context) {
                    return DomainResponseStatusCode::fromString($context->getValue());
                })
        );

        $this->addNormalizer(FluentNormalizer::for(DomainMessageHeaders::class)
            ->as(static function (NormalizationContextInterface $context) {
                /** @var DomainMessageHeaders $value */
                $value = $context->getValue();

                return $value->toArray();
            })
        );

        $this->addDenormalizer(FluentDenormalizer::for(DomainMessageHeaders::class)
            ->as(static function (DenormalizationContextInterface $context) {
                return new DomainMessageHeaders($context->getValue());
            })
        );
    }

    public function normalize($value)
    {
        return $this->normalizer->normalize(new NormalizationContext($value));
    }

    public function denormalize($value, string $className)
    {
        return $this->denormalizer->denormalize(new DenormalizationContext($value, $className));
    }

    /**
     * Adds a new normalizer.
     */
    public function addNormalizer(NormalizerInterface $normalizer): void
    {
        $this->normalizer->addNormalizer($normalizer);
    }

    /**
     * Adds a new denormalizer.
     */
    public function addDenormalizer(DenormalizerInterface $denormalizer): void
    {
        $this->denormalizer->addDenormalizer($denormalizer);
    }
}
