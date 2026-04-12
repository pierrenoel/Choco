<?php 

namespace Choco\Console;

use Choco\Core\Services\MigrationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MigrationCommand extends Command
{
    protected static $defaultName = "make:migration";

    protected function configure()
    {
        $this
            ->setName('make:migration')
            ->setDescription('Run migration')
            ->setHelp('This commands generates the migrations.');
    }

    private function readEntities() : array
    {
        $result = [];

        $path = __DIR__ . "/../Entities";
        $files = scandir($path);

        $entities = array_filter($files, fn($item) => $item !== "." && $item !== "..");

        foreach ($entities as $item) {
            $class = 'Choco\\Entities\\' . pathinfo($item, PATHINFO_FILENAME);

            if (class_exists($class)) {
                $result[] = $class;
            }
        }

        return $result;
    }

    private function generateSQL(array $array) : array 
    {
        $tableSQL = [];

        foreach($array as $item)
        {
            $migrationService = new MigrationService($item);
            $tableSQL[$item] = $migrationService->createTable();
        }

        return $tableSQL;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input,$output);
        $io->title("Runs migration");

        $entities = $this->readEntities();
        $sql = $this->generateSQL($entities);

        $path = __DIR__ . "/../migrations";

        dd($path);

        // Ecrire dans un fichier de migrations
        // Pour chaque ligne du tableau, il faut créer un fichier

        return Command::SUCCESS;
    }
}