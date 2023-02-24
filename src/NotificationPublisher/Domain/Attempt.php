<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

class Attempt
{
    private string $id;

    private DateTime $createdAt;

    public function __construct(
        private readonly Message $message,
        private readonly string $status,
        private readonly string $source,
        private readonly ?bool $isError = false
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new DateTime();
    }
}
