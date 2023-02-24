<?php

declare(strict_types=1);

namespace App\NotificationPublisher\UserInterface\CLI;

use App\NotificationPublisher\Domain\Channel;
use App\NotificationPublisher\Domain\ChannelFlagRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChannelStatus extends Command
{
    private const OPT_CHANNEL = 'channel';

    private const OPT_FLAG = 'flag';

    public function __construct(private readonly ChannelFlagRepositoryInterface $channelFlagRepository)
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('notification-publisher:channel-status')
            ->addOption(
                self::OPT_CHANNEL,
                'c',
                InputOption::VALUE_REQUIRED,
                sprintf('Allowed channels: %s', implode(', ', Channel::getOptions()))
            )
            ->addOption(self::OPT_FLAG, 'f', InputOption::VALUE_REQUIRED, 'Flag: 1 or 0');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $channel = $input->getOption(self::OPT_CHANNEL);
        $value = $input->getOption(self::OPT_FLAG);

        $channelEnum = Channel::from($channel);
        $channelFlag = $this->channelFlagRepository->findByChannelName($channelEnum);
        $channelFlag->switch((bool) $value);

        $this->channelFlagRepository->save($channelFlag);

        $table = new Table($output);
        $table->setHeaders(['Channel', 'Status']);
        $table->addRow([$channel, $channelFlag->isEnabled() ? 'Enabled' : 'Disabled']);
        $table->render();

        return Command::SUCCESS;
    }
}
