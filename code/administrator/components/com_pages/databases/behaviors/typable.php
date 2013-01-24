<?php
class ComPagesDatabaseBehaviorTypable extends KDatabaseBehaviorAbstract
{
    protected $_strategy;

    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        if($config->strategy)
        {
            $identifier = clone $this->getIdentifier();
            $identifier->path = array('database', 'behavior', 'type');
            $identifier->name = $config->strategy;

            $strategy = $this->getService($identifier);
            $this->setStrategy($strategy);
        }
    }

    public function setStrategy(KMixinInterface $strategy)
    {
        $this->_strategy = $strategy;
    }

    public function getStrategy()
    {
        return $this->_strategy;
    }

    public function getHandle()
    {
        $methods = $this->getStrategy()->getMethods();
        foreach($methods as $method)
        {
            if(substr($method, 0, 7) == '_before' || substr($method, 0, 6) == '_after') {
                return spl_object_hash($this);
            }
        }

        return null;
    }

    public function execute($name, KCommandContext $context)
    {
        return $this->getStrategy()->execute($name, $context);
    }

    public function getMethods()
    {
        return $this->getStrategy()->getMethods();
    }

    public function getMixableMethods(KObject $mixer = null)
    {
        return $this->getStrategy()->getMixableMethods();
    }

    public function __call($method, $arguments)
    {
        if(in_array($method, $this->getMixableMethods())) {
            return $this->getStrategy()->$method($arguments);
        }
    }
}