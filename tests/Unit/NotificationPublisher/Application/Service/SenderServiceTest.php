<?php

declare(strict_types=1);

namespace App\Tests\Unit\NotificationPublisher\Application\Service;

use App\NotificationPublisher\Application\Service\SenderService;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Domain\MessageRepositoryInterface;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel;
use App\NotificationPublisher\Infrastructure\Channel\SMSChannel;
use PHPUnit\Framework\TestCase;

class SenderServiceTest extends TestCase
{
    public function testProcessWillExecutedOnlyForEmailChannel(): void
    {
        // when
        $message = new Message('my content');
        $message->addEmailRecipient('aswierc@gmail.com');

        // then
        $emailChannel = $this->createMock(EmailChannel::class);
        $emailChannel
            ->expects(self::once())
            ->method('isEligibleForMessage')
            ->with($message)
            ->willReturn(true);

        $emailChannel
            ->expects(self::once())
            ->method('process')
            ->with($message);

        $smsChannel = $this->createMock(SMSChannel::class);
        $smsChannel->expects(self::never())->method('process');

        $repository = $this->createMock(MessageRepositoryInterface::class);

        // when
        $sender = new SenderService([$emailChannel, $smsChannel], $repository);
        $sender->send($message);
    }
}
