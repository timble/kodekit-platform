<?php
/**
 * @package        Koowa_Command
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Command handler
 *
 * The command handler will translate the command name into a function format and call it for the object class to handle
 * it if the method exists.
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 */
class Command extends Object implements CommandInterface
{
    /**
     * The command priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Constructor.
     *
     * @param  ObjectConfig  $config An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

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
            'priority' => CommandChain::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * @param   string           $name     The command name
     * @param   CommandContext  $context  The command context
     *
     * @return  mixed  Method result if the method exsist, NULL otherwise.
     */
    public function execute($name, CommandContext $context)
    {
        $type   = '';
        $result = null;

        if ($context->getSubject())
        {
            $identifier = clone $context->getSubject()->getIdentifier();

            if ($identifier->path) {
                $type = array_shift($identifier->path);
            } else {
                $type = $identifier->name;
            }
        }

        $parts = explode('.', $name);
        $method = !empty($type) ? '_' . $type . ucfirst(StringInflector::implode($parts)) : '_' . lcfirst(StringInflector::implode($parts));

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