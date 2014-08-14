<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Typable Database Behavior
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseBehaviorTypable extends Library\DatabaseBehaviorAbstract
{
    protected $_strategy;

    protected $_strategies = array();

    protected $_methods = array(
        'getTitle',
        'getDescription',
        'getParams',
        'getLink',
        '_beforeInsert',
        '_beforeUpdate'
    );

    protected $_mixable_methods = array(
        'getTitle',
        'getDescription',
        'getParams',
        'getLink'
    );

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_populateStrategies();
    }

    public static function getInstance(Library\ObjectConfig $config, Library\ObjectManagerInterface $manager)
    {
        $instance = parent::getInstance($config, $manager);

        if (!$manager->isRegistered($config->object_identifier)) {
            $manager->setObject($config->object_identifier, $instance);
        }

        return $manager->getObject($config->object_identifier);
    }

    protected function _populateStrategies()
    {
        foreach (new \DirectoryIterator(__DIR__ . '/type') as $fileinfo)
        {
            if ($fileinfo->isFile() && $fileinfo->getExtension() == 'php')
            {
                $name = $fileinfo->getBasename('.php');
                if ($name != 'abstract' && $name != 'interface')
                {
                    $strategy                 = $this->getObject('com:pages.database.behavior.type.' . $name);
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
        return array_combine($this->_methods, $this->_methods);
    }

    public function getMixableMethods($exclude = array())
    {
        $methods = array_fill_keys($this->_mixable_methods, $this);
        $methods['is' . ucfirst($this->getIdentifier()->name)] = true;

        return $methods;
    }

    public function execute(Library\CommandInterface $command, Library\CommandChainInterface $chain)
    {
        $name = $command->getName();

        if ($name == 'before.insert' || $name == 'before.update')
        {
            $this->setMixer($command->data);

            $type = $this->getType();

            $this->setStrategy($type);
            $return = $this->getStrategy()->setMixer($command->data)->execute($command, $chain);
        }
        else $return = true;

        return $return;
    }

    public function __call($method, $arguments)
    {
        if (in_array($method, $this->_mixable_methods))
        {
            $type = $this->getType();

            $this->setStrategy($type);
            $this->getStrategy()->setMixer($this->getMixer());

            switch (count($arguments)) {
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