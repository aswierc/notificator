<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Exception;

use Exception;

class SMSChannelException extends Exception
{
    public static function undefinedRecipientForChannel(): self
    {
        return new self('undefined recipient for SMS channel');
    }

    public static function providerNotSet(): self
    {
        return new self('provider for sms channel was not set');
    }
}
