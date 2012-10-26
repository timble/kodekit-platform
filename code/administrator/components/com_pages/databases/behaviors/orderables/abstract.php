<?php
class ComPagesDatabaseBehaviorOrderableAbstract extends KDatabaseBehaviorAbstract implements ComPagesDatabaseBehaviorOrderableInterface
{
    public function getMixableMethods(KObject $mixer = null)
    {
        $methods = parent::getMixableMethods($mixer);
        unset($methods['is'.ucfirst($this->getIdentifier()->name)]);
        
        return $methods;
    }
}