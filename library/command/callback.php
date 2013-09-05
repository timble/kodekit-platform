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
 * Callback Object Mixin
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Object
 */
class CommandCallback extends ObjectMixinCallback implements CommandInterface
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
     * @param   ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'priority' => Command::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * If params are passed as a associative array or as a KConfig object they will be merged with the context of the
     * command chain and passed along. If they are passed as an indexed array they will be passed to the callback
     * directly.
     *
     * @param string         $name     The command name
     * @param CommandContext $context  The command context
     * @return boolean
     */
    public function execute( $name, CommandContext $context)
    {
        $result    = true;
        $callbacks = $this->getCallbacks($name);

        foreach($callbacks as $key => $callback)
        {
            $params = $this->_params[$name][$key];

            if(is_array($params) && is_numeric(key($params))) {
                $result = call_user_func_array($callback, $params);
            } else {
                $result = call_user_func($callback,  $context->append($params));
            }

            //Call the callback
            if ( $result === false) {
                break;
            }
        }

        return $result === false ? false : true;
    }
    
    /**
     * Get the methods that are available for mixin.
     *
     * @param  Object $mixer Mixer object
     * @return array An array of methods
     */
    public function getMixableMethods(ObjectMixable $mixer = null)
    {
        $methods = parent::getMixableMethods();

        unset($methods['execute']);
        unset($methods['getPriority']);

        return $methods;
    }

    /**
     * Get the priority of a behavior
     *
     * @return	integer The command priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }

}