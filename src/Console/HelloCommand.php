<?php 

namespace Choco\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class HelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('choco:hello')
            ->setDescription('Say hello to ChocoPHP')
            ->setHelp('This is a test command to verify the CLI is working properly.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('🍫 CHOCO PHP FRAMEWORK');

        $io->text([
            'Welcome to your custom PHP framework.',
            'The CLI is correctly configured and ready to build something great.',
        ]);

        $io->newLine();

        $io->success('Installation verified! Everything is working perfectly.');

        $io->note('Run "php bin/choco list" to see all available commands.');

        return Command::SUCCESS;
    }
}