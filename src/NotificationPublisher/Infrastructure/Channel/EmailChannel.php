<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Channel;

use App\NotificationPublisher\Application\Service\Channel;
use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Application\Service\ChannelFlagService;
use App\NotificationPublisher\Domain\Identifier;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel\EmailProvider;
use App\NotificationPublisher\Infrastructure\Exception\EmailChannelException;
use App\NotificationPublisher\Domain\Channel as ChanelEnum;
use Exception;

class EmailChannel implements Channel
{
    /**
     * @param EmailProvider[] $providers
     */
    public function __construct(
        private readonly iterable $providers,
        private readonly ChannelFlagService $channelFlagService
    ) {
    }

    public function isEligibleForMessage(Message $message): bool
    {
        if (!$message->containsIdentifier(Identifier::TYPE_EMAIL)) {
            return false;
        }

        return $this->channelFlagService->isChannelEnabled(ChanelEnum::EMAIL);
    }

    /**
     * @throws EmailChannelException
     */
    public function process(Message $message): Result
    {
        if (null === $message->getEmailRecipient()) {
            throw EmailChannelException::undefinedRecipientForChannel();
        }

        foreach ($this->providers as $provider) {
            if ($provider->isUp()) {
                try {
                    return $provider->sendMessage($message);
                } catch (Exception $e) {
                    continue;
                }
            }
        }

        throw EmailChannelException::providerNotSet();
    }
}
