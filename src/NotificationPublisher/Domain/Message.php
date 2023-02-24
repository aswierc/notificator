<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Domain;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\Uuid;

class Message
{
    private string $id;
    private DateTimeInterface $createdAt;
    private DateTimeInterface $updatedAt;

    private Collection $identifiers;

    private Collection $attempts;

    public function __construct(
        private readonly string $content
    ) {
        $this->id = Uuid::v4()->toRfc4122();
        $this->identifiers = new ArrayCollection();
        $this->attempts = new ArrayCollection();
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public static function create(string $content, ?string $email = null, ?string $phoneNumber = null): self
    {
        if (empty($phoneNumber) && empty($email)) {
            throw new \InvalidArgumentException(
                'at least one of parameter should be provided (email or phoneNumber)'
            );
        }

        $message = new Message($content);
        if (null !== $phoneNumber) {
            $message->addSMSRecipient($phoneNumber);
        }
        if (null !== $email) {
            $message->addEmailRecipient($email);
        }

        return $message;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getContent(): string
    {
        // I'm not satisfied with that solution
        // the content should not be the same for each type of channel
        // for example email usually contain much more chars than sms etc.
        // but I want to keep it simple without plenty of boilerplate classes

        return $this->content;
    }

    public function addEmailRecipient(string $email): void
    {
        $identifier = Identifier::createTypeEmail($email, $this);
        $this->identifiers->set($identifier->getType(), $identifier);
    }

    public function addSMSRecipient(string $phoneNumber): void
    {
        $identifier = Identifier::createTypeSMS($phoneNumber, $this);
        $this->identifiers->set($identifier->getType(), $identifier);
    }

    public function getSMSRecipient(): ?Identifier
    {
        return $this->identifiers->get(Identifier::TYPE_SMS);
    }

    public function getEmailRecipient(): ?Identifier
    {
        return $this->identifiers->get(Identifier::TYPE_EMAIL);
    }

    public function containsIdentifier(string $identifierType): bool
    {
        return $this->identifiers->containsKey($identifierType);
    }

    public function recordAttempt(string $status, string $source, bool $isError): void
    {
        $this->attempts->add(new Attempt($this, $status, $source, $isError));
    }
}
