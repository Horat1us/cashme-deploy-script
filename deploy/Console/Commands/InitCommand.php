<?php
/**
 * Created by PhpStorm.
 * User: Horat1us
 * Date: 16.07.2017
 * Time: 12:45
 */

namespace Horat1us\Deploy\Console\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize config')
            ->setHelp('This commands allows to create base config with list of projects')
            ->addArgument('Do you want create IP whitelist? (separate multiple IP with a space)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}