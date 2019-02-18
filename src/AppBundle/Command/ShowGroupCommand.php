<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ShowGroupCommand extends Command
{
    protected static $defaultName = 'app:show-group';

    protected function configure()
    {
        $this
             ->setDescription('Displays a group')
             ->setHelp('app:show-group --site={http(s)://domain} --gid={gid}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('gid', null, InputOption::VALUE_REQUIRED, "Group ID");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/showgroup/'.
                   htmlspecialchars($input->getOption('gid'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $fmt = "%-10s%-25s";
        $output->writeln(sprintf($fmt, "id","name"));
        $output->writeln(sprintf($fmt, "--","----"));
        $output->writeln(sprintf($fmt, $jsonresp['id'], $jsonresp['name']));
    }
}
