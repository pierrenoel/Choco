<?php 

namespace Choco\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ServeCommand extends Command 
{
    protected static $defaultName = "choco:serve";

    protected function configure()
    {
       $this
        ->setName('serve')
        ->setDescription('Start the ChocoPHP development server')
        ->addOption(
            'port', 
            'p', 
            InputOption::VALUE_OPTIONAL, 
            'The port to serve the application on', 
            8000
        )
        ->setHelp('This command allows you to run your application locally using the built-in PHP web server.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $port = $input->getOption('port');
        $host = 'localhost';
        $publicPath = 'public';

        $io = new SymfonyStyle($input, $output);

        $io->success('🍫 ChocoPHP Development Server started');

        $io->text([
            "<comment>Local: http://{$host}:{$port}</comment>",
            "Press Ctrl+C to stop the server",
        ]);

        $io->newLine();

        $command = sprintf("php -S %s:%d -t %s", $host, $port, $publicPath);

        passthru($command);

        return Command::SUCCESS;
        
    }
}