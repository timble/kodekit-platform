<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Orderable Database Behavior Class
 *
 * Provides ordering support for closure tables by using a special ordering help of another table
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesDatabaseBehaviorOrderable extends KDatabaseBehaviorAbstract
{
    protected $_strategy;
    
    public function __construct(KConfig $config)
    {
        // Need to set strategy before parent::__construct, otherwise strategy won't be available in getMixableMethods().
        if($config->strategy)
        {
            $identifier = clone $config->service_identifier;
            $identifier->path = array('database', 'behavior', 'orderable');
            $identifier->name = $config->strategy;
            
            $this->setStrategy($config->service_manager->get($identifier, KConfig::unbox($config)));
        }
        
        parent::__construct($config);
    }
    
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'priority'   => KCommand::PRIORITY_LOWEST,
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
    
    public function getMixableMethods(KObject $mixer = null)
    {
        $methods = array_merge(parent::getMixableMethods($mixer), $this->getStrategy()->getMixableMethods($mixer));
        
        unset($methods['getStrategy']);
        
        return $methods;
    }
    
    public function execute($name, KCommandContext $context)
    {
        return $this->getStrategy()->execute($name, $context);
    }
    
    public function setStrategy(ComPagesDatabaseBehaviorOrderableInterface $strategy)
    {
        $this->_strategy = $strategy;
    }
    
    public function getStrategy()
    {
        return $this->_strategy;
    }
}
