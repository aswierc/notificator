<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Query;

readonly class DetailView
{
    public function __construct(
        public string $status,
        public string $source,
        public bool $isError
    ) {
    }
}
