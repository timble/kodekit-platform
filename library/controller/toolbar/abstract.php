<?php
/**
 * @package        Koowa_Controller
 * @subpackage     Toolbar
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Abstract Controller Toolbar Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage     Toolbar
 */
abstract class ControllerToolbarAbstract extends EventSubscriberAbstract implements ControllerToolbarInterface
{
    /**
     * Controller object
     *
     * @var     array
     */
    protected $_controller;

    /**
     * The commands
     *
     * @var array
     */
    protected $_commands;

    /**
     * Constructor.
     *
     * @param  ObjectConfig  $config An associative array of configuration settings or a ObjectConfig instance.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        if (is_null($config->controller))
        {
            throw new \InvalidArgumentException(
                'controller [ControllerInterface] config option is required'
            );
        }

        if(!$config->controller instanceof ControllerInterface)
        {
            throw new \UnexpectedValueException(
                'Controller: '.get_class($config->controller).' does not implement ControllerInterface'
            );
        }

        //Create the commands array
        $this->_commands = array();

        // Set the controller
        $this->_controller = $config->controller;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   ObjectConfig $object An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'controller' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the controller object
     *
     * @return  ControllerInterface
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Get the toolbar's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getIdentifier()->name;
    }

    /**
     * Add a separator
     *
     * @return  ControllerToolbarInterface
     */
    public function addSeparator()
    {
        $command = new ControllerToolbarCommand('separator');
        $this->_commands[] = $command;
        return $command;
    }

    /**
     * Add a command
     *
     * @param   string    $command The command name
     * @param   mixed    $config  Parameters to be passed to the command
     * @return  ControllerToolbarCommand  The command that was added
     */
    public function addCommand($command, $config = array())
    {
        if (!($command instanceof  ControllerToolbarCommand)) {
            $command = $this->getCommand($command, $config);
        }

        //Set the command parent
        $command->setParent($command);

        $this->_commands[] = $command;
        return $command;
    }

    /**
     * Get a command by name
     *
     * @param string $name  The command name
     * @param array $config An optional associative array of configuration settings
     * @return mixed ControllerToolbarCommand if found, false otherwise.
     */
    public function getCommand($name, $config = array())
    {
        //Create the config object
        $command = new ControllerToolbarCommand($name, $config);

        //Attach the command to the toolbar
        $command->setToolbar($this);

        //Find the command function to call
        if (method_exists($this, '_command' . ucfirst($name)))
        {
            $function = '_command' . ucfirst($name);
            $this->$function($command);
        }
        else
        {
            //Don't set an action for GET commands
            if (!isset($command->href))
            {
                $command->append(array(
                    'attribs' => array(
                        'data-action' => $command->getName()
                    )
                ));
            }
        }

        return $command;
    }

    /**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands()
    {
        return $this->_commands;
    }

    /**
     * Get a new iterator
     *
     * @return  \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->getCommands());
    }

    /**
     * Reset the commands array
     *
     * @return  ControllerToolbarAbstract
     */
    public function reset()
    {
        unset($this->_commands);
        $this->_commands = array();
        return $this;
    }

    /**
     * Add a command by it's name
     *
     * @param   string  $method Method name
     * @param   array   $args   Array containing all the arguments for the original call
     * @return mixed
     * @see addCommand()
     */
    public function __call($method, $args)
    {
        $parts = StringInflector::explode($method);

        if ($parts[0] == 'add' && isset($parts[1]))
        {
            $config = isset($args[0]) ? $args[0] : array();
            $command = $this->addCommand(strtolower($parts[1]), $config);
            return $command;
        }

        return parent::__call($method, $args);
    }
}