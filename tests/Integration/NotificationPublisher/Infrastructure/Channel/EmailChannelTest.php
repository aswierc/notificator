<?php

declare(strict_types=1);

namespace App\Tests\Integration\NotificationPublisher\Infrastructure\Channel;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Application\Service\ChannelFlagService;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel\EmailProvider;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailChannelTest extends KernelTestCase
{
    public function testFailover(): void
    {
        self::bootKernel();
        $container = self::getContainer();

        // given
        // - setup first failure provider
        $first = $this->createMock(EmailProvider::class);
        $first->expects(self::once())->method('isUp')->willReturn(true);
        $first->expects(self::once())->method('sendMessage')->willThrowException(new \Exception());

        // - setup working provider
        $second = $this->createMock(EmailProvider::class);
        $second->expects(self::once())->method('isUp')->willReturn(true);
        $second->expects(self::once())->method('sendMessage')->willReturn(new Result('second ok', false));

        $emailChannel = new EmailChannel(
            [$first, $second],
            $container->get(ChannelFlagService::class)
        );

        // when
        $message = Message::create('content', 'aswierc@gmail.com', '48234234234');
        $result = $emailChannel->process($message);

        self::assertInstanceOf(Result::class, $result);
        self::assertEquals('second ok', $result->getStatus());
    }
}
