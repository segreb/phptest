<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class DeleteUserCommand extends Command
{
    protected static $defaultName = 'app:delete-user';

    protected function configure()
    {
        $this
             ->setDescription('Deletes an existing user')
             ->setHelp('app:delete-user --site={http(s)://domain} --uid={uid}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('uid', null, InputOption::VALUE_REQUIRED, "User ID");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/deleteuser/'.htmlspecialchars($input->getOption('uid'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
