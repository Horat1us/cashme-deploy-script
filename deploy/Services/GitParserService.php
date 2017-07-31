<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/29/17
 * Time: 4:08 PM
 */

namespace Horat1us\Deploy\Services;

use Horat1us\Deploy\GitChangedFile;


/**
 * Class GitParserService
 * @package Horat1us\Deploy\Services
 */
class GitParserService
{
    /**
     * @var string
     */
    public $output;

    /**
     * GitParserService constructor.
     * @param string $output
     */
    public function __construct(string $output)
    {
        $this->output = $output;
    }

    /**
     * @return GitChangedFile[]
     */
    public function parse(): array
    {
        $match = preg_match_all('/(delete|create) mode (\d+) (.+)/', $this->output, $matches);

        if (!$match) {
            return [];
        }

        $output = [];
        foreach ($matches[0] as $matchIndex => $fullMatch) {
            $output[$matchIndex] = new GitChangedFile(
                $matches[1][$matchIndex],
                $matches[2][$matchIndex],
                $matches[3][$matchIndex]
            );
        }

        return $output;
    }
}