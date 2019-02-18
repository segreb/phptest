<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ModifyUserCommand extends Command
{
    protected static $defaultName = 'app:modify-user';

    protected function configure()
    {
        $this
             ->setDescription('Modifies an existing user')
             ->setHelp('app:modify-user --site={http(s)://domain} --uid={uid} [--name={username}] [--email={email}]')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('uid', null, InputOption::VALUE_REQUIRED, "User ID")
             ->AddOption('name', null, InputOption::VALUE_OPTIONAL, "User name")
             ->AddOption('email', null, InputOption::VALUE_OPTIONAL, "User email");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $params = '';

        if (trim(htmlspecialchars($input->getOption('name')))<>'') {
            $params = 'name='.htmlspecialchars($input->getOption('name'));
        }

        if (trim(htmlspecialchars($input->getOption('email')))<>'') {
            if ($params<>'') {$params = $params.'&';};
            $params = $params.'email='.htmlspecialchars($input->getOption('email'));
        }

        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/modifyuser/'.htmlspecialchars($input->getOption('uid')).'?'.$params
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
