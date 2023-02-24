<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Command;

use App\NotificationPublisher\Application\Handler\SendMessageHandler;

/**
 * @see SendMessageHandler
 */
class SendMessageCommand
{
    public ?string $content = null;
    public ?string $phoneNumber = null;
    public ?string $email = null;
}
