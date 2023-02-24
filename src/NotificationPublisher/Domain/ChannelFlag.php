<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

class ChannelFlag
{
    private string $channelName;

    public function __construct(Channel $channel, private bool $isEnabled)
    {
        $this->channelName = $channel->value;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function switch(bool $value): void
    {
        $this->isEnabled = $value;
    }
}
