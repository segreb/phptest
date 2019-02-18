<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class DeleteGroupCommand extends Command
{
    protected static $defaultName = 'app:delete-group';

    protected function configure()
    {
        $this
             ->setDescription('Deletes an existing group')
             ->setHelp('app:delete-group --site={http(s)://domain} --gid={gid}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('gid', null, InputOption::VALUE_REQUIRED, "Group ID");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/deletegroup/'.htmlspecialchars($input->getOption('gid'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
