<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    protected function configure()
    {
        $this
             ->setDescription('Creates a new user')
             ->setHelp('app:create-user --site={http(s)://domain} --name={username} --email={email}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /")
             ->AddOption('name', null, InputOption::VALUE_REQUIRED, "User name")
             ->AddOption('email', null, InputOption::VALUE_REQUIRED, "User email");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/createuser'.
                   '?name='.
                   htmlspecialchars($input->getOption('name')).
                   '&email='.
                   htmlspecialchars($input->getOption('email'))
                  );
        $jsonresp = json_decode($curl->response, true);
        $output->writeln($jsonresp["response"]);
    }
}
