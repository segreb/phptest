<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ListGroupCommand extends Command
{
    protected static $defaultName = 'app:list-group';

    protected function configure()
    {
        $this
             ->setDescription('Displays list of group')
             ->setHelp('app:list-group --site={http(s)://domain}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/listgroup'
                  );
        $jsonresp = json_decode($curl->response, true);
        $fmt = "%-10s%-25s";
        $output->writeln(sprintf($fmt, "id","name"));
        $output->writeln(sprintf($fmt, "--","----"));
        foreach($jsonresp['Groups'] as $garr) {
            $output->writeln(sprintf($fmt, $garr['id'], $garr['name']));
        }
    }
}
