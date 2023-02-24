<?php

declare(strict_types=1);

namespace App\Shared\Request;

use InvalidArgumentException;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class JsonContentRequest
{
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getJsonContent(): array
    {
        try {
            return json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            throw new InvalidArgumentException('Invalid json in request');
        }
    }
}
