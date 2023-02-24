<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Service\Channel;

class Result
{
    public function __construct(
        private readonly string $status,
        private readonly bool $isError
    ) {
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isError(): bool
    {
        return $this->isError;
    }
}
