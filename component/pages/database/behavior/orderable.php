<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Orderable Database Behavior Class
 *
 * Provides ordering support for closure tables by using a special ordering help of another table
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
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
            $identifier = clone $config->object_identifier;
            $identifier->path = array('database', 'behavior', 'orderable');
            $identifier->name = $config->strategy;
            
            $this->setStrategy($config->object_manager->getObject($identifier, Library\ObjectConfig::unbox($config)));
        }
        
        parent::__construct($config);
    }
    
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority'   => Library\Command::PRIORITY_LOWEST,
            'auto_mixin' => true,
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
    
    public function getMixableMethods(Library\ObjectMixable $mixer = null)
    {
        $methods = array_merge(parent::getMixableMethods($mixer), $this->getStrategy()->getMixableMethods($mixer));
        
        unset($methods['getStrategy']);
        
        return $methods;
    }
    
    public function execute($name, Library\CommandContext $context)
    {
        return $this->getStrategy()->execute($name, $context);
    }
    
    public function setStrategy(DatabaseBehaviorOrderableInterface $strategy)
    {
        $this->_strategy = $strategy;
    }
    
    public function getStrategy()
    {
        return $this->_strategy;
    }
}
