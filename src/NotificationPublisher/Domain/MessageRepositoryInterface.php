<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

interface MessageRepositoryInterface
{
    public function save(Message $message): void;
}
