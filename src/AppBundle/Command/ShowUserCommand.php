<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ShowUserCommand extends Command
{
    protected static $defaultName = 'app:show-user';

    protected function configure()
    {
        $this
             ->setDescription('Displays a user')
             ->setHelp('app:show-user --site={http(s)://domain} --uid={uid}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('uid', null, InputOption::VALUE_REQUIRED, "User ID");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/showuser/'.
                   htmlspecialchars($input->getOption('uid'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $fmt = "%-10s%-25s%-35s%-20s";
        $output->writeln(sprintf($fmt, "id","name","email","groups"));
        $output->writeln(sprintf($fmt, "--","----","-----","------"));
        $output->writeln(sprintf($fmt, $jsonresp['id'], $jsonresp['name'], $jsonresp['email'], implode(',', $jsonresp['groups'])));
    }
}
