<?php

declare(strict_types=1);

namespace App\Tests\Feature\NotificationPublisher\UserInterface\API;

use Doctrine\DBAL\Connection;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PublisherControllerTest extends WebTestCase
{
    public function testSendRequest(): void
    {
        $faker = Factory::create();

        $email = $faker->email();
        $phone = $faker->phoneNumber();

        $client = static::createClient();
        $client->request(
            'post',
            '/notification-publisher',
            content: json_encode([
                'email' => $email,
                'phoneNumber' => $phone,
                'content' => $faker->text(),
            ])
        );

        $this->assertResponseIsSuccessful();

        /** @var Connection $connection */
        $connection = self::getContainer()->get(Connection::class);
        $savedEmailIdent = $connection->fetchAssociative(
            sprintf("select * from app_message_identifier i where i.identifier = '%s'", $email)
        );
        self::assertIsArray($savedEmailIdent);
        self::assertNotEmpty($savedEmailIdent);

        $savedPhoneIdent = $connection->fetchAssociative(
            sprintf("select * from app_message_identifier i where i.identifier = '%s'", $phone)
        );
        self::assertIsArray($savedPhoneIdent);
        self::assertNotEmpty($savedPhoneIdent);
    }
}
