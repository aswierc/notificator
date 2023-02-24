<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\NotificationPublisher\Domain\Channel;
use App\NotificationPublisher\Domain\ChannelFlag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ChannelFlagFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->persist(new ChannelFlag(Channel::SMS, true));
        $manager->persist(new ChannelFlag(Channel::EMAIL, true));
        $manager->flush();
    }
}
