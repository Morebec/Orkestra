<?php

namespace Morebec\Orkestra\Workflow\Query;

use Morebec\ValueObjects\BasicEnum;

/**
 * @method static self OR()
 * @method static self AND()
 */
final class ExpressionOperator extends BasicEnum
{
    /** @var string */
    private const AND = 'AND';

    /** @var string */
    private const OR = 'OR';
}
