<?php
/**
 * Created by PhpStorm.
 * User: asier
 * Date: 10/06/18
 * Time: 18:41
 */

namespace App\Command;


use App\Service\Greeting;
use Symfony\Bridge\PhpUnit\TextUI\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelloCommand extends Command
{
    /**
     * @var Greeting
     */
    private $greeting;
    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:say-hello')
            ->setDescription('Says hello to the user')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln([
            'Hello from the app',
            '==================',
            ''
        ]);
        $output->writeln($this->greeting->greet($name));
    }
}