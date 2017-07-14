<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 7/14/17
 * Time: 6:21 PM
 */

namespace Horat1us\Deploy;

use Symfony\Component\HttpFoundation\Request;

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
     * @var Request
     */
    protected $request;

    /**
     * Access constructor.
     * @param Request $request
     * @param array $config
     */
    public function __construct(Request $request, array $config = [])
    {
        $this->request = $request;

        parent::__construct($config);
    }

    /**
     * @return bool
     */
    public function isAllowed(): bool
    {
        $currentIp = $this->request->getClientIp();
        if (is_null($currentIp)) {
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
        foreach ($ranges as $ip) {
            if ($ip === $current) {
                return true;
            }
        }
        return false;
    }
}