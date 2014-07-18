<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Hittable Database Behavior
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Nooku\Library\Database
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
