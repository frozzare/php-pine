<?php

namespace Pine;

class Data
{
    /**
     * Arguments array.
     */
    protected $argv = [];

    /**
     * Flag arguments array.
     *
     * @param array
     */
    protected $flags = [];

    /**
     * Data constructor.
     *
     * @param array $argv
     */
    public function __construct($argv)
    {
        $this->parse($argv);
    }

    /**
     * Get argument values.
     *
     * @return array
     */
    public function argv()
    {
        return $this->argv;
    }

    /**
     * Get flag by key.
     *
     * @param  string $key
     * @param  string $default
     *
     * @return mixed
     */
    public function flag($key, $default = '')
    {
        return $this->flags[$key] ?? $default;
    }

    /**
     * Parse flag arguments.
     *
     * @param  array $argv
     */
    protected function parse(array $argv = [])
    {
        $flags = [];
        $argv2 = [];

        for ($i = 0; $i < count($argv); $i++) {
            if (preg_match('/^(?:--|-)(([^=])|([^=]+))=(.*)/', $argv[$i], $match)) {
                $key = empty($match[2]) ? $match[3] : $match[2];
                $flags[$key] = $match[4];
                continue;
            }

            $argv2[] = $argv[$i];
        }

        $this->argv = $argv2;
        $this->flags = $flags;
    }
}
