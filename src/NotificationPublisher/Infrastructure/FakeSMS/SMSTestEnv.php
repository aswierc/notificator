<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\FakeSMS;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\SMSChannel\SMSProvider;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
class SMSTestEnv implements SMSProvider
{
    public function isUp(): bool
    {
        return true;
    }

    public function sendMessage(Message $message): Result
    {
        return new Result(get_class(), false);
    }
}
