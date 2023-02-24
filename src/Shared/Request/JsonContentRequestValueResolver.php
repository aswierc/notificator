<?php

declare(strict_types=1);

namespace App\Shared\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class JsonContentRequestValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === JsonContentRequest::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield new JsonContentRequest($request);
    }
}
