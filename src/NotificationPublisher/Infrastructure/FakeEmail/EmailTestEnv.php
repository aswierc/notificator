<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\FakeEmail;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel\EmailProvider;
use App\NotificationPublisher\Infrastructure\Channel\SMSChannel\SMSProvider;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
class EmailTestEnv implements EmailProvider
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
