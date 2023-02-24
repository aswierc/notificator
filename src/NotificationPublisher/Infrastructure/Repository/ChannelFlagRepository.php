<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Repository;

use App\NotificationPublisher\Domain\Channel;
use App\NotificationPublisher\Domain\ChannelFlag;
use App\NotificationPublisher\Domain\ChannelFlagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ChannelFlagRepository extends ServiceEntityRepository implements ChannelFlagRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChannelFlag::class);
    }

    public function findByChannelName(Channel $channel): ?ChannelFlag
    {
        return $this->find($channel->value);
    }

    public function save(ChannelFlag $channelFlag): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->persist($channelFlag);
        $entityManager->flush();
    }
}
