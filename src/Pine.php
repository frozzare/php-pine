<?php

namespace Pine;

class Pine
{
    /**
     * Prefix (namespace and/or class name) of function.
     *
     * @var string
     */
    protected $prefix = '';

    /**
     * Pine tasks.
     *
     * @var array
     */
    protected $tasks = [];

    /**
     * Pine class instance.
     *
     * @var \Pine\Pine
     */
    protected static $instance;

    /**
     * Get Pine class instance.
     *
     * @return \Pine\Pine
     */
    public static function instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Pine construct.
     */
    protected function __construct()
    {
        $files = ['Pinefile', 'Pinefile.php'];

        foreach ($files as $file) {
            $this->file($file);

            if ($this->exists()) {
                break;
            }
        }
    }

    /**
     * Call that task after specified task runs.
     *
     * @param string $after
     * @param string $before
     */
    public function after($after, $before)
    {
        $after = str_replace('_', ':', $after);
        $before = str_replace('_', ':', $before);

        if (!isset($this->tasks[$after])) {
            $this->tasks[$after] = $this->createTask($after);
        }

        $this->tasks[$after]->addAfter($this->createTask($before));
    }

    /**
     * Call that task before specified task runs.
     *
     * @param string $before
     * @param string $after
     */
    public function before($before, $after)
    {
        $before = str_replace('_', ':', $before);
        $after = str_replace('_', ':', $after);

        if (!isset($this->tasks[$before])) {
            $this->tasks[$before] = $this->createTask($before);
        }

        $this->tasks[$before]->addBefore($this->createTask($after));
    }

    /**
     * Create task.
     *
     * @param  string $name
     *
     * @return \Pine\Task
     */
    protected function createTask($name)
    {
        return new Task($this->prefixName($name));
    }

    /**
     * Determine if Pine file exists.
     *
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->file);
    }

    /**
     * Set Pine file.
     *
     * @param  string $file
     *
     * @return \Pine\Pine
     */
    public function file($file)
    {
        if (file_exists($file)) {
            $this->file = $file;
        } else {
            $this->file = realpath(getcwd() . '/' . basename($file));
        }

        if ($this->exists()) {
            $this->prefix = $this->findPrefix($file);
        }

        return $this;
    }

    /**
     * Find prefix (namespace name and/or class name) from file.
     *
     * @param  string $file
     *
     * @return string
     */
    protected function findPrefix($file)
    {
        if (! file_exists($file)) {
            return '';
        }

        $content         = file_get_contents($file);
        $tokens          = token_get_all($content);
        $class_name      = '';
        $namespace_name  = '';
        $i               = 0;
        $len             = count($tokens);

        for (; $i < $len; $i++) {
            if ($tokens[$i][0] === T_NAMESPACE) {
                for ($j = $i + 1; $j < $len; $j++) {
                    if ($tokens[$j][0] === T_STRING) {
                        $namespace_name .= '\\' . $tokens[$j][1];
                    } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                        break;
                    }
                }
            }
            if ($tokens[$i][0] === T_CLASS) {
                for ($j = $i + 1; $j < $len; $j++) {
                    if ($tokens[$j] === '{') {
                        $class_name = $tokens[$i + 2][1];
                    }
                }
            }
        }

        if (empty($class_name)) {
            return $namespace_name;
        }

        if (empty($namespace_name)) {
            return $class_name;
        }

        return $namespace_name . '\\' . $class_name;
    }

    /**
     * Run Pine task.
     *
     * @param  string $name
     * @param  array  $argv
     *
     * @return bool
     */
    public function run($name, array $argv = [])
    {
        $file = $this->file;

        if (!file_exists($file)) {
            return false;
        }

        require_once $file;

        if (!isset($this->tasks[$name])) {
            $this->tasks[$name] = $this->createTask($name);
        }

        return $this->tasks[$name]->run(new Data($argv));
    }

    /**
     * Prefix name of function.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function prefixName($name)
    {
        $prefix = $this->prefix;

        if (empty($prefix)) {
            return $name;
        }

        if (method_exists($prefix, $name)) {
            return $prefix . '::' .$name;
        }

        return $prefix . '\\'.$name;
    }
}
