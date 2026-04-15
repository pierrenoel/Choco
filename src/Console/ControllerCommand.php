<?php 

namespace Choco\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ControllerCommand extends Command 
{
    protected static $defaultName = "make:controller";

    protected function configure()
    {
        $this->setName("make:controller")
        ->setDescription("Make a new controller")
        ->setHelp("This command creates automatically new controller")
        ->addOption("model", "m", InputOption::VALUE_NONE, "Create the model relating to the controller");
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $io = new SymfonyStyle($input,$output);

        $io->title("Make a new controller");

        $name = $io->ask("What is the name of the controller?");

        $name = \ucfirst($name);

        $file = "src/Controllers/{$name}.php";

        if(\file_exists($file)){
            $io->error("Controller already exsits");
            return Command::FAILURE;
        }
        else {
            $controllerContent = ["<?php","","namespace Choco\Controllers;","use Choco\Core\Controller;","","class {$name} extends Controller","{","","}"];
            \file_put_contents("src/Controllers/{$name}.php",implode("\n",$controllerContent));

            // Create the model
            $isResource = $input->getOption("model");
            if($isResource){
                $modelContent = ["<?php","","namespace Choco\Repositories;","use Choco\Core\Repository;","","class {$name} extends Repository","{","","}"];
                \file_put_contents("src/Repositories/{$name}Repository.php",implode("\n",$modelContent));
            }

            $io->success("Controller {$name} created");
        }

        return Command::SUCCESS;
    }
}