<?php

namespace Tests\Morebec\Orkestra\Modeling;

use Morebec\Orkestra\Modeling\Enum;

/**
 * Fake Enum class used to test enums.
 *
 * @method static NAME()
 * @method static VALUE()
 */
class FakeEnum extends Enum
{
    public const NAME = 'NAME_VALUE';

    public const VALUE = 'VALUE';
}
