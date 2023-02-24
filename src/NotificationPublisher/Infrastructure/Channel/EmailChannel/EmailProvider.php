<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Channel\EmailChannel;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;

interface EmailProvider
{
    public function isUp(): bool;
    public function sendMessage(Message $message): Result;
}
