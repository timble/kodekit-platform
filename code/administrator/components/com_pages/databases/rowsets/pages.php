<?php
/**
 * @version     $Id: pages.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Database Rowset Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
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
            $query = $needle['link'];
            unset($needle['link']);

            $pages = parent::find($needle);
            foreach($pages as $page)
            {
                foreach($query as $parts)
                {
                    $result = $page;
                    foreach($parts as $key => $value)
                    {
                        if(!(isset($page->link->query[$key]) && $page->link->query[$key] == $value))
                        {
                            $result = null;
                            break;
                        }
                    }

                    if(!is_null($result)) {
                        break(2);
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
