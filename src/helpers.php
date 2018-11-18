<?php

/**
 * Get Pine instance.
 *
 * @return \Pine\Pine
 */
function pine()
{
    return \Pine\Pine::instance();
}

/**
 * Call that task before specified task runs.
 *
 * @param string $before
 * @param string $after
 */
function before($after, $before)
{
    pine()->before($after, $before);
}

/**
 * Call that task after specified task runs.
 *
 * @param string $after
 * @param string $before
 */
function after($before, $after)
{
    pine()->after($before, $after);
}
