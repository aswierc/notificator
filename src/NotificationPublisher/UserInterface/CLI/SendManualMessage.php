<?php

declare(strict_types=1);

namespace App\NotificationPublisher\UserInterface\CLI;

use App\NotificationPublisher\Application\Service\SenderService;
use App\NotificationPublisher\Domain\Message;
use App\NotificationPublisher\Infrastructure\Query\MessageDetailQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

class SendManualMessage extends Command
{
    private const OPT_CONTENT = 'content';
    private const OPT_PHONE_NUMBER = 'phone-number';
    private const OPT_EMAIL = 'email';

    public function __construct(
        private readonly SenderService $senderService,
        private readonly MessageDetailQuery $messageDetailQuery
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('notification-publisher:manual')
            ->addOption(self::OPT_CONTENT, 'c', InputOption::VALUE_REQUIRED, 'Content message')
            ->addOption(self::OPT_PHONE_NUMBER, 'p', InputOption::VALUE_OPTIONAL, 'Phone number with prefix - 48555666777')
            ->addOption(self::OPT_EMAIL, 'm', InputOption::VALUE_OPTIONAL, 'Email address')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $content = $input->getOption(self::OPT_CONTENT);
        $phoneNumber = $input->getOption(self::OPT_PHONE_NUMBER);
        $email = $input->getOption(self::OPT_EMAIL);

        try {
            $message = Message::create($content, $email, $phoneNumber);
        } catch (\InvalidArgumentException $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        $this->senderService->send($message);
        $details = $this->messageDetailQuery->getDetailsByMessageId($message->getId());


        $table = new Table($output);
        $table->setHeaders(['Status', 'Source', 'Is Error']);
        foreach ($details as $detail) {
            $table->addRow([$detail->status, $detail->source, (int) $detail->isError]);
        }
        $table->render();

        return Command::SUCCESS;
    }
}
