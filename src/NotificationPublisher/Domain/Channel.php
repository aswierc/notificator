<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

enum Channel: string
{
    case SMS = 'sms';
    case EMAIL = 'email';

    public static function getOptions(): array
    {
        return [
            self::SMS->value,
            self::EMAIL->value,
        ];
    }
}
