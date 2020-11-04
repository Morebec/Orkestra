<?php

namespace Morebec\Orkestra\Normalization\Denormalizer;

/**
 * Normalizes a scalar value.
 * This normalizer is quite simple, only ensuring the values received are scalar values and returning them
 * since they are already considered normalized.
 */
class ScalarValueDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function denormalize(DenormalizationContextInterface $context)
    {
        if (!$this->supports($context)) {
            throw new UnsupportedDenormalizerValueException($context, $this);
        }

        // Return it as is, normalized forms only contain primitives.
        return $context->getValue();
    }

    public function supports(DenormalizationContextInterface $context): bool
    {
        return is_scalar($context->getValue()) || $context->getTypeName() === 'scalar';
    }
}
