<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/16/17
 * Time: 6:27 PM
 */

namespace Horat1us\Deploy\Exceptions;


use Throwable;

/**
 * Class GitNotFoundException
 * @package Horat1us\Deploy\Exceptions
 */
class InvalidGitRepository extends GitException
{
    /**
     * GitNotFoundException constructor.
     *
     * @param string $path
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $path, $message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = "$path is not a git repository";
        }

        parent::__construct($path, $message, $code, $previous);
    }
}