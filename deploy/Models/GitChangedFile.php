<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 4:10 PM
 */

namespace Horat1us\Deploy;


/**
 * Class GitChangedFile
 * @package Horat1us\Deploy
 */
class GitChangedFile
{
    /**
     * @var string
     */
    public $mode;

    /**
     * @var string
     */
    public $permissions;

    /**
     * @var string
     */
    public $filename;

    /**
     * GitChangedFile constructor.
     * @param string $mode
     * @param string $permissions
     * @param string $filename
     */
    public function __construct(string $mode, string $permissions, string $filename)
    {
        $this->mode = $mode;
        $this->permissions = $permissions;
        $this->filename = $filename;
    }
}