<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\AWS\SES;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\EmailChannel\EmailProvider;
use Aws\Ses\SesClient;
use Exception;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'prod')]
#[When(env: 'dev')]
readonly class SESEmailProvider implements EmailProvider
{
    private SesClient $client;

    public function __construct(
        string $key,
        string $secret
    ) {
        $this->client = new SesClient([
            'version' => '2010-12-01',
            'region' => 'us-east-1',
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
        ]);
    }

    public function isUp(): bool
    {
        return true;
    }

    public function sendMessage(Message $message): Result
    {
        try {
            $this->client->sendEmail([
                'Destination' => [
                    'ToAddresses' => [
                        $message->getEmailRecipient()->getIdentifier(),
                    ],
                ],
                'Message' => [
                    'Body' => [
                        'Text' => [
                            'Data' => $message->getContent(),
                        ],
                    ],
                    'Subject' => [
                        'Data' => 'Test Email', //TODO - message should provide also subject'
                    ],
                ],
                'Source' => 'aswierc@gmail.com', // you need to change to your source, todo move it to env
            ]);

            return new Result('ok', false);

        } catch (Exception $e) {
            return new Result('error', true);
        }
    }
}
