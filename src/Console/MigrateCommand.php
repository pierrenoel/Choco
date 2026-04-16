<?php 

namespace Choco\Console;

use Choco\Core\Repositories\BaseRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrateCommand extends Command
{
    protected static $defaultName = "white:migrate";

    protected function configure()
    {
        $this
            ->setName('white:migrate')
            ->setDescription('Run migrations')
            ->setHelp('This commands runs the migrations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input,$output);
        $io->title("Migrate");

        $root = dirname(__DIR__, 2);
        $path = $root . "/migrations/database.sql";

        if(!\file_exists($path)){
            $io->error("Nothing to migrate");
            return Command::FAILURE;
        }

        $baseRespository = new BaseRepository();
        $baseRespository->generateDatabase($path);
        
        return Command::SUCCESS;
    }
}