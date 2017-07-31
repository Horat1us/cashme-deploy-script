<?php
/**
 * Created by PhpStorm.
 * User: Horat1us
 * Date: 16.07.2017
 * Time: 12:45
 */

namespace Horat1us\Deploy\Console\Commands;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Validator\Constraints as Assert;

class InitCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Initialize config')
            ->setHelp('This commands allows to create base config with list of projects');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $question = new Question("What IP do you want to put in whitelist? ");
        $question->setValidator(function ($value) {
            if(!preg_match('/(\d{1,3}\.){3}(\d{1,3})/', $value)) {
                throw new RuntimeException("Not valid IP address");
            }
        });

        $helper->ask($input, $output, $question);
    }
}