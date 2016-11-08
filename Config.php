<?php

defined("ROOT") || die("Not allowed");

/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 07.11.16
 * Time: 19:14
 */
class Config
{
    protected $fields = [];

    /**
     * Config constructor.
     * @param array $fields
     */
    public function __construct($fields = [])
    {
        $this->_fields = $fields;

        $this->_readFields();
    }


    /**
     * Loading values for remote and branch settings
     *
     * @throws UnexpectedValueException
     * @return bool
     */
    protected function _readFields()
    {
        $filename = $this->_generateConfigFilename();

        $fileContents = file_get_contents($filename);

        $json = json_decode($fileContents);
        $res = count(array_diff($this->_fields, array_keys((array)$json)));
        $res2 = !$res;
        if ($json === NULL || count(array_diff($this->_fields, array_keys((array)$json)))) {
            throw new UnexpectedValueException("Wrong JSON file contents");
        }

        foreach ($this->_fields as $field) {
            $this->{"_{$field}"} = $json->{$field};
        }

        return true;
    }

    /**
     * @param bool $sample
     * @throws UnexpectedValueException
     * @return string
     */
    protected function _generateConfigFilename($sample = false)
    {
        $filename = ROOT . ($sample ? '/settings.sample.json' : '/settings.json');

        if (file_exists($filename)) {
            return $filename;
        }

        if ($sample) {
            throw new \UnexpectedValueException("Settings JSON file does not exists #{$filename}");
        }
        else {
            return $this->_generateConfigFilename(true);
        }
    }

    public function __get($name)
    {
        return $this->{"_{$name}"};
    }
}