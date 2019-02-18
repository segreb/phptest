<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ListUserCommand extends Command
{
    protected static $defaultName = 'app:list-user';

    protected function configure()
    {
        $this
             ->setDescription('Displays list of user')
             ->setHelp('app:list-user --site={http(s)://domain}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/listuser'
                  );
        $jsonresp = json_decode($curl->response, true);
        $fmt = "%-10s%-25s%-35s%-20s";
        $output->writeln(sprintf($fmt, "id","name","email","groups"));
        $output->writeln(sprintf($fmt, "--","----","-----","------"));
        foreach($jsonresp['Users'] as $uarr) {
            $output->writeln(sprintf($fmt, $uarr['id'], $uarr['name'], $uarr['email'], implode(',', $uarr['groups'])));
        }
    }
}
