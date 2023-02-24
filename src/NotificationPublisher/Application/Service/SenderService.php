<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Service;

use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Domain\MessageRepositoryInterface;

readonly class SenderService
{
    /**
     * @param iterable|Channel[] $channels
     */
    public function __construct(
        private iterable $channels,
        private MessageRepositoryInterface $messageRepository
    ) {
    }

    public function send(Message $message): void
    {
        foreach ($this->channels as $channel) {
            if ($channel->isEligibleForMessage($message)) {
                $result = $channel->process($message);

                $message->recordAttempt(
                    $result->getStatus(),
                    get_class($channel),
                    $result->isError()
                );
            }
        }

        $this->messageRepository->save($message);
    }
}
