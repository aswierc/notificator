<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Service;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;

interface Channel
{
    public function isEligibleForMessage(Message $message): bool;
    public function process(Message $message): Result;
}
