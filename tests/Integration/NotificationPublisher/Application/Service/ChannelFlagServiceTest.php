<?php

declare(strict_types=1);

namespace App\Tests\Integration\NotificationPublisher\Application\Service;

use App\NotificationPublisher\Application\Service\ChannelFlagService;
use App\NotificationPublisher\Domain\Channel;
use App\NotificationPublisher\Domain\ChannelFlagRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChannelFlagServiceTest extends KernelTestCase
{
    public function testToggleFlags(): void
    {
        self::bootKernel();

        /** @var ChannelFlagRepositoryInterface $repo */
        $repo = self::getContainer()->get(ChannelFlagRepositoryInterface::class);

        /** @var ChannelFlagService $service */
        $service = self::getContainer()->get(ChannelFlagService::class);

        $email = $repo->findByChannelName(Channel::EMAIL);
        $email->switch(false);
        $repo->save($email);

        self::assertFalse($service->isChannelEnabled(Channel::EMAIL));

        $email->switch(true);
        $repo->save($email);

        self::assertTrue($service->isChannelEnabled(Channel::EMAIL));
    }
}
