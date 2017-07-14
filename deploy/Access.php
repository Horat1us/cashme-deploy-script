<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 6:21 PM
 */

namespace Horat1us\Deploy;

use Vectorface\Whip\IpRange\Ipv4Range;
use Vectorface\Whip\Whip;


/**
 * Class Access
 * @package Horat1us\Deploy
 */
class Access extends Component
{
    /**
     * @var string[]
     */
    public $forbidden = [];

    /**
     * @var string[]
     */
    public $allowed = ['127.0.0.1'];

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        $whip = new Whip(Whip::REMOTE_ADDR);

        $currentIp = $whip->getValidIpAddress();
        if ($currentIp === false) {
            return false;
        }

        if ($this->check($this->allowed, $currentIp)) {
            return true;
        }

        if (empty($this->allowed) || $this->check($this->forbidden, $currentIp)) {
            return false;
        }

        return true;
    }

    /**
     * @param string[] $ranges
     * @param string $current
     * @return bool
     */
    protected function check(array $ranges, string $current)
    {
        foreach ($ranges as $range) {
            $range = new Ipv4Range($range);
            if ($range->containsIp($current)) {
                return true;
            }
        }
        return false;
    }
}