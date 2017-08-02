<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/2/17
 * Time: 3:35 PM
 */

namespace Horat1us\Deploy\Validators;

use Horat1us\Deploy\Exceptions\InvalidGitRepository;


/**
 * Class GitDirectoryValidator
 * @package Horat1us\Deploy\Validators
 */
class GitPathValidator implements ValidatorInterface
{
    /**
     * @var string
     */
    public $path;

    /**
     * GitDirectoryValidator constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @throws InvalidGitRepository
     * @return void
     */
    public function validate()
    {
        $gitPath = $this->path . '.git';

        if (
            !file_exists($this->path) || !is_dir($this->path)
            || !file_exists($gitPath) || !is_dir($gitPath)
        ) {
            throw new InvalidGitRepository($this->path);
        }
    }
}