<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Query;

use App\NotificationPublisher\Application\Query\DetailView;
use App\NotificationPublisher\Application\Query\MessageDetailQueryInterface;
use Doctrine\DBAL\Connection;
use PDO;

class MessageDetailQuery implements MessageDetailQueryInterface
{
    public function __construct(private readonly Connection $connection)
    {
    }

    public function getDetailsByMessageId(string $messageId): array
    {
        $qb = $this->connection->createQueryBuilder();

        $qb->select([
            'a.status',
            'a.source',
            'a.is_error',
        ])
            ->from('app_message_attempt', 'a')
            ->join('a', 'app_message', 'm', 'm.id = a.message_id')
            ->where('m.id = :message_id')
            ->setParameter('message_id', $messageId, PDO::PARAM_STR);

        $dbalResult = $qb->executeQuery();
        $rows = $dbalResult->fetchAllAssociative();

        if (empty($rows)) {
            return [];
        }

        $result = [];
        foreach ($rows as $row) {
            $result[] = new DetailView(
                $row['status'],
                $row['source'],
                $row['is_error']
            );
        }

        return $result;
    }
}
