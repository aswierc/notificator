<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Channel;

use App\NotificationPublisher\Application\Service\Channel;
use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Application\Service\ChannelFlagService;
use App\NotificationPublisher\Domain\Channel as ChanelEnum;
use App\NotificationPublisher\Domain\Identifier;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\SMSChannel\SMSProvider;
use App\NotificationPublisher\Infrastructure\Exception\SMSChannelException;

class SMSChannel implements Channel
{
    /**
     * @param SMSProvider[] $providers
     */
    public function __construct(
        private readonly iterable $providers,
        private readonly ChannelFlagService $channelFlagService
    ) {
    }

    public function isEligibleForMessage(Message $message): bool
    {
        if (!$message->containsIdentifier(Identifier::TYPE_SMS)) {
            return false;
        }

        return $this->channelFlagService->isChannelEnabled(ChanelEnum::SMS);
    }

    /**
     * @throws SMSChannelException
     */
    public function process(Message $message): Result
    {
        if (null === $message->getSMSRecipient()) {
            throw SMSChannelException::undefinedRecipientForChannel();
        }

        foreach ($this->providers as $provider) {
            if ($provider->isUp()) {
                try {
                    return $provider->sendMessage($message);
                } catch (\Exception $e) {
                    // here I'd like to set some flag for the provider
                    // and check in the next request if the provider's API came back to work
                    continue;
                }
            }
        }

        throw SMSChannelException::providerNotSet();
    }
}
