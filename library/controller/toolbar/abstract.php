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
 * Abstract Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerToolbarAbstract extends Command implements ControllerToolbarInterface
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
     * The toolbar type
     *
     * @var array
     */
    protected $_type;

    /**
     * Constructor.
     *
     * @param  ObjectConfig  $config An associative array of configuration settings or a ObjectConfig instance.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Create the commands array
        $this->_commands = array();

        //Set the toolbar type
        $this->_type = $config->type;

        // Set the controller
        $this->setController($config->controller);
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
            'type'       => 'toolbar',
            'controller' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Command handler
     *
     * This function translates the command name to a command handler function of the format '_beforeController[Command]'
     * or '_afterController[Command]. Command handler functions should be declared protected.
     *
     * @param 	string           $name	    The command name
     * @param 	CommandContext  $context 	The command context
     * @return 	boolean Always returns TRUE
     */
    final public function execute($name, CommandContext $context)
    {
        $identifier = clone $context->getSubject()->getIdentifier();
        $type = array_shift($identifier->path);

        $parts  = explode('.', $name);
        $method = '_'.$parts[0].ucfirst($type).ucfirst($parts[1]);

        if(method_exists($this, $method)) {
            $this->$method($context);
        }

        return true;
    }

    /**
     * Get the toolbar type
     *
     * @return  string
     */
    public function getType()
    {
        return $this->_type;
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
     * Get the controller
     *
     * @return  ControllerInterface
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * Set the controller
     *
     * @return  ControllerToolbarAbstract
     */
    public function setController(ControllerInterface $controller)
    {
        $this->_controller = $controller;
        return $this;
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

        $this->_commands[$command->getName()] = $command;
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
        if(!isset($this->_commands[$name]))
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
        }
        else $command = $this->_commands[$name];

        return $command;
    }

    /**
     * Check if a command exists
     *
     * @param string $name  The command name
     * @return boolean True if the command exists, false otherwise.
     */
    public function hasCommand($name)
    {
        return isset($this->_commands[$name]);
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
     * Returns the number of toolbar commands.
     *
     * Required by the Countable interface
     *
     * @return int
     */
    public function count()
    {
        return count($this->_commands);
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