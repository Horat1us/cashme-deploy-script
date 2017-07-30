<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 7:25 PM
 */

namespace Horat1us\Deploy\Exceptions;


use Throwable;

/**
 * Class GitParseException
 * @package Horat1us\Deploy\Exceptions
 */
class GitParseException extends GitException
{
    /**
     * @var string
     */
    protected $output;

    /**
     * GitParseException constructor.
     *
     * @param string $output
     * @param string $path
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $output, string $path, $message = "Can not parse git output", $code = 0, Throwable $previous = null)
    {
        $this->output = $output;

        parent::__construct($path, $message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }
}