<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Repository;

use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Domain\MessageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository implements MessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function save(Message $message): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($message);
        $entityManager->flush();
    }
}
