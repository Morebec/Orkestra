<?php

namespace Morebec\Orkestra\Messaging\Validation;

use Morebec\Orkestra\Messaging\DomainMessageHeaders;
use Morebec\Orkestra\Messaging\DomainMessageInterface;
use Morebec\Orkestra\Messaging\DomainResponseInterface;
use Morebec\Orkestra\Messaging\Middleware\DomainMessageBusMiddlewareInterface;
use Morebec\Orkestra\Modeling\Collection;

class ValidateDomainMessageMiddleware implements DomainMessageBusMiddlewareInterface
{
    /**
     * @var DomainMessageValidatorInterface[]
     */
    private $validators;

    public function __construct(iterable $validators = [])
    {
        $this->validators = [];
        foreach ($validators as $validator) {
            $this->validators[] = $validator;
        }
    }

    public function handle(DomainMessageInterface $domainMessage, DomainMessageHeaders $headers, callable $next): DomainResponseInterface
    {
        $validators = new Collection($this->validators);

        $errors = $validators
            // Filter validators supporting this domain message
            ->filter(static function (DomainMessageValidatorInterface $validator) use ($domainMessage, $headers) {
                return $validator->supports($domainMessage, $headers);
            })
            // Of these, validate all and return their error lists
            ->map(static function (DomainMessageValidatorInterface $validator) use ($domainMessage, $headers) {
                return $validator->validate($domainMessage, $headers);
            })
            // Filter the error lists that are not empty
            ->filter(static function (DomainMessageValidationErrorList $errors) {
                return !$errors->isEmpty();
            })
            // Finally, merge all errors into a single collection of errors
            ->flatten();

        if (!$errors->isEmpty()) {
            // Convert this collection of errors back into a DomainMessageValidationErrorList and pass this in the response.
            return new InvalidDomainMessageResponse(new DomainMessageValidationErrorList($errors));
        }

        return $next($domainMessage, $headers);
    }
}
