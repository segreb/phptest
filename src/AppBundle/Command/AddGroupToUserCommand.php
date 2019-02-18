<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class AddGroupToUserCommand extends Command
{
    protected static $defaultName = 'app:add-group-to-user';

    protected function configure()
    {
        $this
             ->setDescription('Adds a group to an existing user')
             ->setHelp('app:add-group-to-user --site={http(s)://domain} --uid={uid} --gid={gid}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('uid', null, InputOption::VALUE_REQUIRED, "User ID")
             ->AddOption('gid', null, InputOption::VALUE_REQUIRED, "Group ID");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/addgrouptouser/'.
                   htmlspecialchars($input->getOption('uid')).'/'.
                   htmlspecialchars($input->getOption('gid'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
