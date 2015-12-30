<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Controller
 */
abstract class ControllerToolbarAbstract extends CommandHandlerAbstract implements ControllerToolbarInterface
{
    /**
     * Controller object
     *
     * @var     array
     */
    private $__controller;

    /**
     * The commands
     *
     * @var array
     */
    private $__commands;

    /**
     * The toolbar type
     *
     * @var array
     */
    protected $_type;

    /**
     * The toolbar title
     *
     * @var array
     */
    protected $_title;

    /**
     * Constructor.
     *
     * @param  ObjectConfig $config An associative array of configuration settings or a ObjectConfig instance.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Create the commands array
        $this->__commands = array();

        //Set the toolbar type
        $this->_type = $config->type;

        //Set the toolbar title
        $this->_title = $config->title;

        // Set the controller
        $this->setController($config->controller);

        // Add the commands
        foreach ($config->commands as $key => $value)
        {
            if (is_numeric($key)) {
                $this->addCommand($value);
            } else {
                $this->addCommand($key, $value);
            }
        }
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
            'title'      => '',
            'controller' => null,
            'commands'   => array(),
        ));

        parent::_initialize($config);
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
     * Get the toolbar's title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Set the toolbar's title
     *
     * @return ControllerToolbarAbstract
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }

    /**
     * Get the controller
     *
     * @return  ControllerInterface
     */
    public function getController()
    {
        return $this->__controller;
    }

    /**
     * Set the controller
     *
     * @return  ControllerToolbarAbstract
     */
    public function setController(ControllerInterface $controller)
    {
        $this->__controller = $controller;
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
        $this->__commands[] = $command;
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

        $this->__commands[$command->getName()] = $command;
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
        if(!isset($this->__commands[$name]))
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
        else $command = $this->__commands[$name];

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
        return isset($this->__commands[$name]);
    }

    /**
     * Get the list of commands
     *
     * @return  array
     */
    public function getCommands()
    {
        return $this->__commands;
    }

    /**
     * Get a new iterator
     *
     * @return  \RecursiveArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getCommands());
    }

    /**
     * Reset the commands array
     *
     * @return ControllerToolbarAbstract
     */
    public function reset()
    {
        unset($this->__commands);
        $this->__commands = array();
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
        return count($this->getCommands());
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