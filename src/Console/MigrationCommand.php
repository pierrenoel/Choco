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
        $fkSQL = [];

        foreach($array as $item)
        {
            $migrationService = new MigrationService($item);
            $tableSQL[] = $migrationService->createTable();
            $fk = $migrationService->foreignKey();
            if(!empty($fk)) $fkSQL[] = $fk;
        }

        return [
            "sql" => $tableSQL,
            "fksql" => $fkSQL
        ];

    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input,$output);
        $io->title("Runs migration");

        $entities = $this->readEntities();
        $sql = $this->generateSQL($entities);

        $mergedArray = array_merge($sql["sql"],$sql["fksql"]);

        $root = dirname(__DIR__, 2);
        $path = $root . "/migrations/database.sql";

        if(\file_exists($path)){
            $io->error("This file already exsits");
            return Command::FAILURE;
        }
        
        $file = \file_put_contents($path,implode("\n",$mergedArray));
          
        return Command::SUCCESS;
    }
}