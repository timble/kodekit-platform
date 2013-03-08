<?php

use Nooku\Framework;

class ComPagesDatabaseBehaviorTypable extends Framework\DatabaseBehaviorAbstract
{
    protected $_strategy;

    protected $_strategies = array();

    protected $_methods = array(
        'getTypeTitle',
        'getTypeDescription',
        'getParams',
        'getLink',
        '_beforeTableInsert',
        '_beforeTableUpdate'
    );

    protected $_mixable_methods = array(
        'getTypeTitle',
        'getTypeDescription',
        'getParams',
        'getLink'
    );

    public function __construct(Framework\Config $config)
    {
        parent::__construct($config);

        $this->_populateStrategies();
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'auto_mixin' => true
        ));

        parent::_initialize($config);
    }

    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
    {
        $instance = parent::getInstance($config, $manager);

        if(!$manager->has($config->service_identifier)) {
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    protected function _populateStrategies()
    {
        foreach(new DirectoryIterator(__DIR__.'/types') as $fileinfo)
        {
            if($fileinfo->isFile() && $fileinfo->getExtension() == 'php')
            {
                $name = $fileinfo->getBasename('.php');
                if($name != 'abstract' && $name != 'interface')
                {
                    $strategy = $this->getService('com://admin/pages.database.behavior.type.'.$name);
                    $this->_strategies[$name] = $strategy;
                }
            }
        }
    }

    public function setStrategy($strategy)
    {
        $this->_strategy = $strategy;

        return $this;
    }

    public function getStrategy()
    {
        return $this->_strategies[$this->_strategy];
    }

    public function getHandle()
    {
        return spl_object_hash($this);
    }

    public function getMethods()
    {
        return array_fill_keys($this->_methods, $this);
    }

    public function getMixableMethods(Framework\Object $mixer = null)
    {
        $methods = array_fill_keys($this->_mixable_methods, $this);
        $methods['is'.ucfirst($this->getIdentifier()->name)] = function() { return true; };

        return $methods;
    }

    public function execute($name, Framework\CommandContext $context)
    {
        if($name == 'before.insert' || $name == 'before.update')
        {
            $this->setMixer($context->data);

            if(is_array($this->getType()))
            {
                $type = $this->getType();
                $type = $type['name'];
            }
            else $type = $this->type;

            $this->setStrategy($type);
            $return = $this->getStrategy()->setMixer($context->data)->execute($name, $context);
        }
        else $return = true;

        return $return;
    }

    public function __call($method, $arguments)
    {
        if(in_array($method, $this->_mixable_methods))
        {
            if(is_array($this->getType()))
            {
                $type = $this->getType();
                $type = $type['name'];
            }
            else $type = $this->type;

            $this->setStrategy($type);
            $this->getStrategy()->setMixer($this->getMixer());

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