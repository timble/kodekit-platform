<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Extensions;

use Nooku\Library;

/**
 * Settings Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Extensions
 */
class DatabaseRowsetSettings extends Library\DatabaseRowsetAbstract
{       
	/**
     * Test existence of a key
     *
     * @param  string  $key The key name.
     * @return boolean
     */
    public function __isset($key)
    {
        return $this->_object_set->offsetExists($key);
    }
    
	/**
     * Get a row by key
     *
     * @param   string  $key The key name.
     * @return  string  The corresponding value.
     */
    public function __get($key)
    {
        return $this->_object_set->offsetGet($key);
    }
    
	/**
     * Set the rowset data based on a named array/hash
     *
     * @param   array   $data     An associative array of data
     * @param   boolean $modified If TRUE, update the modified information for each column being set. Default TRUE
     * @return  Library\DatabaseRowsetAbstract
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