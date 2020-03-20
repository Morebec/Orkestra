<?php

namespace Morebec\Orkestra\Workflow\Query;

use Morebec\ValueObjects\BasicEnum;

/**
 * @method static self EQUAL()
 * @method static self NOT_EQUAL()
 * @method static self LESS_THAN()
 * @method static self GREATER_THAN()
 * @method static self GREATER_OR_EQUAL()
 * @method static self LESS_OR_EQUAL()
 * @method static self IN()
 * @method static self NOT_IN()
 * @method static self CONTAINS()
 * @method static self NOT_CONTAINS()
 */
final class TermOperator extends BasicEnum
{
    /** @var string */
    public const EQUAL = '===';

    /** @var string */
    public const NOT_EQUAL = '!==';

    /** @var string */
    public const LESS_THAN = '<';

    /** @var string */
    public const GREATER_THAN = '>';

    /** @var string */
    public const LESS_OR_EQUAL = '<=';

    /** @var string */
    public const GREATER_OR_EQUAL = '>=';

    /** @var string */
    public const IN = 'in';

    /** @var string */
    public const NOT_IN = 'not_in';

    /** @var string Operator for arrays */
    public const CONTAINS = 'contains';

    /** @var string */
    public const NOT_CONTAINS = 'not_contains';

    public static function __callStatic($method, $arguments): self
    {
        return new static(\constant("self::$method"));
    }
}
