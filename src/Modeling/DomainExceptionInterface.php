<?php

namespace Morebec\Orkestra\Modeling;

use Morebec\Orkestra\Messaging\DomainResponseStatusCode;

/**
 * General Domain Exceptions. These exceptions in most cases indicate
 * violated business invariants. They will trigger a {@link DomainResponseStatusCode::REFUSED()} response.
 */
interface DomainExceptionInterface extends \Throwable
{
}
