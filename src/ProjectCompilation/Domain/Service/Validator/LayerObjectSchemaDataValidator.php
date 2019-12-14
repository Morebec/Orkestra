<?php


namespace Morebec\Orkestra\ProjectCompilation\Domain\Service\Validator;

use Morebec\Orkestra\Core\Util\Validation\ValidationResult;
use Morebec\Orkestra\ProjectCompilation\Domain\Model\Entity\LayerObject\LayerObjectSchema;

/**
 * Validates the data of a layer object schema.
 * This is a step right before converting the data to a LayerObject
 */
class LayerObjectSchemaDataValidator
{
    public function validate(LayerObjectSchema $layer): ValidationResult
    {

    }
}