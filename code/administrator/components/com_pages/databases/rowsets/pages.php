<?php
/**
 * @version     $Id: pages.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Database Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseRowsetPages extends KDatabaseRowsetDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'row_cloning' => false
        ));

        parent::_initialize($config);
    }
    
    public function find($needle)
    {
        if(is_array($needle) && array_key_exists('link', $needle) && is_array($needle['link']))
        {
            $result = null;
            
            // Iterate through the pages.
            foreach($this as $row)
            {
                // Iterate through the needles.
                foreach($needle as $key => $value)
                {
                    if($key == 'link')
                    {
                        // Iterate through the query parts array.
                        foreach($value as $query_parts)
                        {
                            $result = $row;
                            
                            // Iterate through the query parts.
                            foreach($query_parts as $query_key => $query_value)
                            {
                                if(!(isset($row->link->query[$query_key]) && $row->link->query[$query_key] == $query_value))
                                {
                                    $result = null;
                                    break;
                                }
                            }
                            
                            if(!is_null($result)) {
                                break(3);
                            }
                        }
                    }
                    else
                    {
                        if(!in_array($row->{$key}, (array) $value)) {
                            break;
                        }
                    }
                }
            }
            
            return $result;
        }
        else return parent::find($needle);
    }
    
    public function __call($method, $arguments)
    {
        // Call these methods directly on the rowset.
        $methods = array('setRoute', 'setActive', 'getActive', 'getHome', 'isAuthorized');
        if(in_array($method, $methods) && isset($this->_mixed_methods[$method]))
        {
            $object = $this->_mixed_methods[$method];
            $result = null;

            $object->setMixer($this);
            
            switch(count($arguments))
            {
                case 0:
                    $result = $object->$method();
                    break;
                case 1:
                    $result = $object->$method($arguments[0]);
                    break;
                case 2:
                    $result = $object->$method($arguments[0], $arguments[1]);
                    break;
             }
        }
        else $result = parent::__call($method, $arguments);
        
        return $result;
    }
}
