<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Handler;

use App\NotificationPublisher\Application\Command\SendMessageCommand;
use App\NotificationPublisher\Application\Service\SenderService;
use App\NotificationPublisher\Domain\Message;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendMessageHandler
{
    public function __construct(private SenderService $senderService)
    {
    }

    public function __invoke(SendMessageCommand $command): void
    {
        $this->senderService->send(Message::create(
            $command->content,
            $command->email,
            $command->phoneNumber
        ));
    }
}
