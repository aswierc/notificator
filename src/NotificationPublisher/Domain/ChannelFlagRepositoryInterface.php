<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

interface ChannelFlagRepositoryInterface
{
    public function findByChannelName(Channel $channel): ?ChannelFlag;

    public function save(ChannelFlag $channelFlag): void;
}
