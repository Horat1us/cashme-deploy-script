<?php

defined('ROOT') || die('Not allowed');

/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 07.11.16
 * Time: 18:59
 */
class Access implements AccessProvider
{
    /**
     * Contains current users IP
     * @var string
     */
    protected $_ip;

    /**
     * Contains allowed IPs for current user
     * @var array
     */
    protected $_allowedIPs = [];

    /**
     * Setting current IP. If not IP sent we'll detect it
     * @param bool $ip
     * @return $this
     */
    public function setCurrentIP($ip = false)
    {
        if ($ip) {
            if (!static::_validateIP($ip)) {
                throw new UnexpectedValueException("Wrong IP passed to Access::__construct #{$ip}");
            }

            $this->_ip = $ip;
        }
        else {
            $this->_ip = static::_loadIP();
        }

        return $this;
    }

    /**
     * Checking if current user IP in allowed list
     * @return bool
     */
    public function checkAccess()
    {
        return count($this->_allowedIPs) ? in_array($this->_ip, $this->_allowedIPs) : true;
    }

    /**
     * @param array $ips
     * @return $this
     */
    public function setAllowedIPs($ips = [])
    {
        foreach ((array)$ips as $ip) {
            if (!static::_validateIP($ip)) {
                throw new UnexpectedValueException("Wrong IP passed to Access::setAllowedIPs #{$ip}");
            }
        }

        $this->_allowedIPs = (array)$ips;

        return $this;
    }

    /**
     * @param array $config
     * @return Access
     */
    public function setConfig(array $config = [])
    {
        return $this->setAllowedIPs($config);
    }

    /**
     * @param bool $value
     */
    public function isAllowed($value = false)
    {
        $this->setCurrentIP($value)
            ->isAllowed();
    }


    /**
     * @return bool|string
     */
    protected static function _loadIP()
    {
        $ip_keys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR',];

        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) !== true) {
                continue;
            }

            foreach (explode(',', $_SERVER[$key]) as $ip) {
                // trim for safety measures
                $ip = trim($ip);
                // attempt to validate IP
                if (static::_validateIP($ip)) {
                    return $ip;
                }
            }
        }
        throw new UnexpectedValueException("Unable to detect users IP");
    }

    protected static function _validateIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;
    }
}