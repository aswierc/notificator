<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Application\Query;

interface MessageDetailQueryInterface
{
    /**
     * @param string $messageId
     * @return DetailView[]
     */
    public function getDetailsByMessageId(string $messageId): array;
}
