<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Command Invoker
 *
 * The command invoker will translate the command name to a method name, format and call it for the object class to
 * handle it if the method exists.
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Command
 */
abstract class CommandInvokerAbstract extends Object implements CommandInvokerInterface
{
    /**
     * The command priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Object constructor
     *
     * @param ObjectConfig $config Configuration options
     * @throws \InvalidArgumentException
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Set the command priority
        $this->_priority = $config->priority;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * @param   string          $name     The command name
     * @param   Command  $context  The command context
     *
     * @return  mixed  Method result if the method exists, NULL otherwise.
     */
    public function execute($name, Command $context)
    {
        $result = null;

        $parts = explode('.', $name);
        $method =  '_' . ucfirst(StringInflector::implode($parts));

        //If the method exists call the method and return the result
        if (in_array($method, $this->getMethods())) {
            $result = $this->$method($context);
        }

        return $result;
    }

    /**
     * Get the priority of the command
     *
     * @return  integer The command priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }
}