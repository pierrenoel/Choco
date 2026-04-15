<?php 

namespace Choco\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class EntityCommand extends Command
{
    protected static $defaultName = "make:entity";

    protected function configure()
    {
        $this
            ->setName('make:entity')
            ->setDescription('Create an entity')
            ->setHelp('This commands generates an entity.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input,$output);
        $io->title("This helps you to create a new entity");     
        
        $name = $io->ask("Entity Name");

        // Check if entity already exsits
        $file = "src/Entities/{$name}.php";

        $name = \ucfirst($name);

        if(\file_exists($file)){
            $io->error("Entity already exsits");
            return Command::FAILURE;
        }else{
           $content = ["<?php","","namespace Choco\Entities;","","class {$name} ","{","","}"];
            \file_put_contents("src/Entities/{$name}.php",implode("\n",$content));
        }

        // TODO: ask what are the properties
        
        return Command::SUCCESS;
    }
}