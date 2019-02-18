<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ModifyGroupCommand extends Command
{
    protected static $defaultName = 'app:modify-group';

    protected function configure()
    {
        $this
             ->setDescription('Modifies an existing group')
             ->setHelp('app:modify-group --site={http(s)://domain} --gid={gid} [--name={groupname}]')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('gid', null, InputOption::VALUE_REQUIRED, "Group ID")
             ->AddOption('name', null, InputOption::VALUE_OPTIONAL, "Group name");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/modifygroup/'.htmlspecialchars($input->getOption('gid')).'?'.
                   'name='.htmlspecialchars($input->getOption('name'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
