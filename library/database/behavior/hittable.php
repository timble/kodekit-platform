<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Hittable Database Behavior
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Kodekit\Library\Database
 */
class DatabaseBehaviorHittable extends DatabaseBehaviorAbstract
{
    /**
     * Check if the behavior is supported
     *
     * Behavior requires a 'hits'
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $table = $this->getMixer();

        //Only check if we are connected with a table object, otherwise just return true.
        if($table instanceof DatabaseTableInterface)
        {
            if(!$table->hasColumn('hits'))  {
                return false;
            }
        }

        return true;
    }

    /**
     * Increase hit counter by 1
     *
     * Requires a 'hits' column
     */
    public function hit()
    {
        $this->hits++;

        if(!$this->isNew()) {
            $this->save();
        }

        return $this->getMixer();
    }
}
