<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\FakeSMS;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\SMSChannel\SMSProvider;
use Exception;

class SMSFailed implements SMSProvider
{
    public function isUp(): bool
    {
        return false;
    }

    public function sendMessage(Message $message): Result
    {
        throw new Exception('shouldn\t be executed');
    }
}
