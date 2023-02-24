<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

use DateTime;
use Symfony\Component\Uid\Uuid;

class Identifier
{
    public const TYPE_SMS = 'sms';
    public const TYPE_EMAIL = 'email';

    private string $id;

    private DateTime $createdAt;

    private function __construct(
        private readonly Message $message,
        private readonly string $identifier,
        private readonly string $type
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->createdAt = new DateTime();
    }

    public static function createTypeSMS(string $identifier, Message $message): self
    {
        return new self($message, $identifier, self::TYPE_SMS);
    }

    public static function createTypeEmail(string $identifier, Message $message): self
    {
        return new self($message, $identifier, self::TYPE_EMAIL);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
