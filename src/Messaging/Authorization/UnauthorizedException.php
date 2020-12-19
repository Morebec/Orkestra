<?php

namespace Morebec\Orkestra\Messaging\Authorization;

use Morebec\Orkestra\Modeling\DomainExceptionInterface;

class UnauthorizedException extends \RuntimeException implements DomainExceptionInterface
{
}
