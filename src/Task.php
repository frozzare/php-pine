<?php

namespace Pine;

class Task
{
    /**
     * Tasks to run after.
     *
     * @var array
     */
    protected $after = [];

    /**
     * Tasks to run before.
     *
     * @var array
     */
    protected $before = [];

    /**
     * Create a new task.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = str_replace(':', '_', $name);
    }

    /**
     * Add new after task.
     *
     * @param \Pine\Task $task
     */
    public function addAfter($task)
    {
        array_unshift($this->after, $task);
    }

    /**
     * Add new before task.
     *
     * @param \Pine\Task $task
     */
    public function addBefore($task)
    {
        array_unshift($this->before, $task);
    }

    /**
     * Run task.
     *
     * @param  array $argv
     *
     * @return bool
     */
    public function run(array $argv = [])
    {
        if (!is_callable($this->name)) {
            return false;
        }

        foreach ($this->before as $task) {
            if (!$task->run($argv)) {
                return false;
            }
        }

        call_user_func($this->name, $argv);

        foreach ($this->after as $task) {
            if (!$task->run($argv)) {
                return false;
            }
        }

        echo "\n";

        return true;
    }
}
