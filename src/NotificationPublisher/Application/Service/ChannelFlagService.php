<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Service;

use App\NotificationPublisher\Domain\Channel as ChannelEnum;
use App\NotificationPublisher\Domain\ChannelFlagRepositoryInterface;

class ChannelFlagService
{
    public function __construct(private readonly ChannelFlagRepositoryInterface $channelFlagRepository)
    {
    }

    public function isChannelEnabled(ChannelEnum $channel): bool
    {
        $flag = $this->channelFlagRepository->findByChannelName($channel);

        if (null !== $flag) {
            return $flag->isEnabled();
        }

        return false;
    }
}
