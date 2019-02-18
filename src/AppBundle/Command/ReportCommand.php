<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Curl\Curl;

class ReportCommand extends Command
{
    protected static $defaultName = 'app:report';

    protected function configure()
    {
        $this
             ->setDescription('Displays the list of users of each group')
             ->setHelp('app:report --site={http(s)://domain}')
        ;
        $this
             ->AddOption('site', null, InputOption::VALUE_REQUIRED, "Site's domain with http(s)://, without trailing /");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $curl = new Curl();
        $curl->get(htmlspecialchars($input->getOption('site')).
                   '/userapi/report'
                  );
        $jsonresp = json_decode($curl->response, true);
        $fmtt = "%-10s%-25s%-50s";
        $fmtg = "%-10s%-25s";
        $output->writeln(sprintf($fmtt, "id","name","users"));
        $output->writeln(sprintf($fmtt, "--","----","-----"));
        foreach($jsonresp['Groups'] as $garr) {
            $output->write(sprintf($fmtg, $garr['id'], $garr['name']));
            $gc = 0;
            foreach($garr['users'] as $uarr) {
                if ($gc==0) {
                    $output->writeln($uarr['id'].', '.$uarr['name']);
                } else {
                    $output->writeln(sprintf("%-35s"," ").$uarr['id'].', '.$uarr['name']);
                }
                $gc++;
            }
            if ($gc==0) {$output->writeln("");};
        }
    }
}
