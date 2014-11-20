<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Orderable Database Behavior Class
 *
 * Provides ordering support for closure tables by using a special ordering help of another table
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorOrderable extends Library\DatabaseBehaviorAbstract
{
    protected $_strategy;
    
    public function __construct(Library\ObjectConfig $config)
    {
        // Need to set strategy before parent::__construct, otherwise strategy won't be available in getMixableMethods().
        if($config->strategy)
        {
            $identifier = $config->object_identifier->toArray();
            $identifier['path'] = array('database', 'behavior', 'orderable');
            $identifier['name'] = $config->strategy;
            
            $this->setStrategy($config->object_manager->getObject($identifier, Library\ObjectConfig::unbox($config)));
        }
        
        parent::__construct($config);
    }
    
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => self::PRIORITY_LOWEST,
            'strategy'   => 'flat',
            'table'      => null,
            'columns'    => array()
        ));

        parent::_initialize($config);
    }
    
    public function getMethods()
    {
        $methods = parent::getMethods();
        
        foreach($this->getStrategy()->getMethods() as $method)
        {
            if(substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
                $methods[] = $method;
            }
        }
        
        return $methods;
    }

    public function getMixableMethods($exclude = array())
    {
        $methods = array_merge(parent::getMixableMethods($exclude), $this->getStrategy()->getMixableMethods());
        unset($methods['getStrategy']);
        
        return $methods;
    }

    public function execute(Library\CommandInterface $command, Library\CommandChainInterface $chain)
    {
        return $this->getStrategy()->execute($command, $chain);
    }
    
    public function setStrategy(DatabaseBehaviorOrderableInterface $strategy)
    {
        $this->_strategy = $strategy;
    }
    
    public function getStrategy()
    {
        return $this->_strategy;
    }

    public function __call($method, $arguments)
    {
        if(in_array($method, $this->getStrategy()->getMixableMethods()))
        {
            switch(count($arguments))
            {
                case 0:
                    $return = $this->getStrategy()->$method();
                    break;

                case 1:
                    $return = $this->getStrategy()->$method($arguments[0]);
                    break;

                default:
                    $return = call_user_func_array(array($this->getStrategy(), $method), $arguments);
                    break;
            }
        }
        else $return = parent::__call($method, $arguments);

        return $return;
    }
}
