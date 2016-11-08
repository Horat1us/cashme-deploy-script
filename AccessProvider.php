<?php

interface AccessProvider
{
    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config = []);

    /**
     * @param mixed $value
     * @return bool
     */
    public function isAllowed($value = false);
}