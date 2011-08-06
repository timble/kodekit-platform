<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Aros Database Table Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Groups
 */

class ComGroupsDatabaseTableNodes extends KDatabaseTableAbstract
{
    public function lock()
    {
        // TODO: Improve locking of tables when Nooku implements it
        $this->getDatabase()->execute('LOCK TABLES `'.$this->getPrefixedBase().'` WRITE, `'.$this->getPrefixedName().'` READ');
    }
        
    public function unlock()
    {
        $this->getDatabase()->execute('UNLOCK TABLES');
    }

    /**
     * A Temporary function to get the table with temporary prefix. 
     *      TODO: It will be unnecessary when NFW's query builder for UPDATE and DELETE is done
     *
     * @return boolean  If successfull return TRUE, otherwise FALSE
     */
    public function getPrefixedBase()
    {
        return '#__'.$this->getBase();
    }
    
    public function getPrefixedName()
    {
        return '#__'.$this->getName();
    }
}