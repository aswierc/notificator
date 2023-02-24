<?php

declare(strict_types=1);

namespace App\NotificationPublisher\Infrastructure\Twilio;

use App\NotificationPublisher\Application\Service\Channel\Result;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Channel\SMSChannel\SMSProvider;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'prod')]
#[When(env: 'dev')]
class TwilioSMSProvider implements SMSProvider
{
    private Client $client;
    public function __construct(
        private readonly string $from,
        string $accountSid,
        string $token,
    ) {
        $this->client = new Client($accountSid, $token);
    }

    public function isUp(): bool
    {
        // it is the place to check the service through a circuit breaker
        // we can implement here own solution or check something from github for example:
        // https://github.com/leocarmo/circuit-breaker-php

        return true;
    }

    public function sendMessage(Message $message): Result
    {
        $phoneNumber = $message->getSMSRecipient()->getIdentifier();

        try {
            $this->client->messages->create($phoneNumber, [
                'Body' => $message->getContent(),
                'From' => $this->from,
            ]);

            return new Result('ok', false);
        } catch (RestException $e) {
            return new Result('error', true);
        }
    }
}
