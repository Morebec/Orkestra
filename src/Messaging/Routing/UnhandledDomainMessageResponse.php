<?php

namespace Morebec\Orkestra\Messaging\Routing;

use Morebec\Orkestra\Messaging\AbstractDomainResponse;
use Morebec\Orkestra\Messaging\DomainResponseStatusCode;

/**
 * Response indicating that no Domain Message Handler received a given Domain Message.
 */
class UnhandledDomainMessageResponse extends AbstractDomainResponse
{
    public function __construct()
    {
        parent::__construct(DomainResponseStatusCode::SKIPPED());
    }
}
