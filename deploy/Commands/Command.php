<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 8:05 PM
 */

namespace Horat1us\Deploy\Commands;

use Horat1us\Deploy\Factories\TriggerFactory;
use Horat1us\Deploy\Project;
use Horat1us\Deploy\Trigger;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


/**
 * Class Command
 * @package Horat1us\Deploy
 */
class Command
{
    /**
     * @var string
     */
    public $command;

    /**
     * @var string[]
     */
    public $triggers;

    /**
     * @param Project $project
     * @throws ProcessFailedException
     * @return string|null
     */
    public function execute(Project $project)
    {
        if (array_reduce($this->getTriggers(), function (bool $carry, Trigger $trigger) use ($project) {
            return $carry || $trigger->triggered($project->changedFiles);
        }, false)) {
            return null;
        }

        $process = new Process($this->command, $project->path);
        return $process
            ->mustRun()
            ->getOutput();
    }

    /**
     * @return Trigger[]
     */
    public function getTriggers(): array
    {
        return array_map(function ($trigger) {
            return TriggerFactory::instantiate($trigger);
        }, (array)$this->triggers);
    }
}