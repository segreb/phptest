<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class CreateGroupCommand extends Command
{
    protected static $defaultName = 'app:create-group';

    protected function configure()
    {
        $this
             ->setDescription('Creates a new group')
             ->setHelp('app:create-group --site={http(s)://domain} --name={groupname}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('name', null, InputOption::VALUE_REQUIRED, "Group name");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/creategroup'.
                   '?name='.
                   htmlspecialchars($input->getOption('name'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
