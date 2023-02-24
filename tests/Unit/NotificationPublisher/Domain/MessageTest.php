<?php

declare(strict_types=1);

namespace App\Tests\Unit\NotificationPublisher\Domain;

use App\NotificationPublisher\Domain\Identifier;
use App\NotificationPublisher\Domain\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    public function testIfMessageCanRealizedAssignedIdentifiers(): void
    {
        $message = new Message('test content');
        self::assertFalse($message->containsIdentifier(Identifier::TYPE_EMAIL));

        $message->addEmailRecipient('test@gmail.com');
        self::assertTrue($message->containsIdentifier(Identifier::TYPE_EMAIL));
    }
}
