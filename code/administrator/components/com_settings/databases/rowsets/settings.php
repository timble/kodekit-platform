<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Settings Database Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 */
class ComSettingsDatabaseRowsetSettings extends KDatabaseRowsetAbstract
{       
	/**
     * Test existence of a key
     *
     * @param  string  The key name.
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->_object_set->offsetExists($key);
    }
    
	/**
     * Get a row by key
     *
     * @param   string  The key name.
     * @return  string  The corresponding value.
     */
    public function __get($key)
    {
        return $this->_object_set->offsetGet($key);
    }
    
	/**
     * Set the rowset data based on a named array/hash
     *
     * @param   array   An associative array of data
     * @param   boolean If TRUE, update the modified information for each column being set.
     *                  Default TRUE
     * @return  KDatabaseRowsetAbstract
     */
     public function setData( $data, $modified = true )
     { 
         //Set the data in the rows
        if(isset($data[$this->getIdentifier()->name])) 
        { 
            foreach($data[$this->getIdentifier()->name] as $key => $data) {
                 $this->_object_set->offsetGet($key)->setData($data, $modified); 
             }   
        }
        else parent::setData($data, $modified);
        
        return $this;
    }
}