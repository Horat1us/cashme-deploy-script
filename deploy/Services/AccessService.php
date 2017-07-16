<?php

namespace Horat1us\Deploy\Services;

/**
 * Class AccessService
 */
class AccessService
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
     * Access constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        configure_object($this, $config);
    }

    /**
     * @param string|null $currentIp
     * @return bool
     */
    public function isAllowed(string $currentIp = null): bool
    {
        return !is_null($currentIp)
            && (
                // Check for whitelist
                $this->check($this->allowed, $currentIp)
                // If we have no whitelist we need to use blacklist
                || (empty($this->allowed) && !$this->check($this->forbidden, $currentIp))
            );
    }

    /**
     * @param string[] $ranges
     * @param string $current
     * @return bool
     */
    protected function check(array $ranges, string $current)
    {
        return array_reduce(
            $ranges,
            function (bool $match, string $ip) use ($current) {
                return $match || $ip === $current;
            }
        );
    }
}