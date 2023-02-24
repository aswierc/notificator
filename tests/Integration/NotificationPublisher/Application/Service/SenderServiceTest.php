<?php

declare(strict_types=1);

namespace App\Tests\Integration\NotificationPublisher\Application\Service;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Application\Service\SenderService;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Domain\MessageRepositoryInterface;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel;
use App\NotificationPublisher\Infrastructure\Query\MessageDetailQuery;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SenderServiceTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    public function testChannelWasNotSet(): void
    {
        $container = self::getContainer();

        // given
        $sender = new SenderService(
            [],
            $container->get(MessageRepositoryInterface::class)
        );

        $message = new Message('my test message');
        $message->addEmailRecipient('aswierc@gmail.com');

        // when
        $sender->send($message);

        // then
        $details = $container->get(MessageDetailQuery::class)->getDetailsByMessageId($message->getId());
        self::assertEmpty($details);
    }

    public function testEmailChannelWasSet(): void
    {
        $container = self::getContainer();

        // given
        $sender = new SenderService(
            [$this->createEmailChannel()],
            $container->get(MessageRepositoryInterface::class)
        );

        $message = new Message('my test message');
        $message->addEmailRecipient('aswierc@gmail.com');

        // when
        $sender->send($message);

        // then
        $details = $container->get(MessageDetailQuery::class)->getDetailsByMessageId($message->getId());
        self::assertNotEmpty($details);
        self::assertCount(1, $details);
    }

    private function createEmailChannel(): EmailChannel
    {
        $emailChannel = $this->createMock(EmailChannel::class);
        $emailChannel->expects(self::once())
            ->method('isEligibleForMessage')
            ->willReturn(true)
        ;
        $emailChannel->expects(self::once())
            ->method('process')
            ->willReturn(new Result('ok', false));

        return $emailChannel;
    }
}
