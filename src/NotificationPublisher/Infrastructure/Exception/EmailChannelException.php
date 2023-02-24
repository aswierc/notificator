<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Exception;

use Exception;

class EmailChannelException extends Exception
{
    public static function undefinedRecipientForChannel(): self
    {
        return new self('undefined recipient for Email channel');
    }

    public static function providerNotSet(): self
    {
        return new self('provider for email channel was not set');
    }
}
