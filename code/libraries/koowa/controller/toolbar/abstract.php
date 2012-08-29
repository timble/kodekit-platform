<?php
/**
* @version      $Id$
* @package		Koowa_Controller
* @subpackage 	Toolbar
* @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Abstract Controller Toolbar Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage 	Toolbar
 * @uses        KInflector
 */
abstract class KControllerToolbarAbstract extends KEventSubscriberAbstract implements KControllerToolbarInterface
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
     * @param  KConfig 	An associative array of configuration settings or a KConfig instance.
     */
    public function __construct(KConfig $config = null)
    {
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);
        
        if(is_null($config->controller)) {
			throw new KMixinException('controller [KController] option is required');
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
     * @param   KConfig $object An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'controller'    => null,
        ));
        
        parent::_initialize($config);
    }
    
	/**
     * Get the controller object
     * 
     * @return  \KControllerInterface
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
     * @return  \KControllerToolbarInterface
     */
    public function addSeparator()
    {
        $command = new KControllerToolbarCommand('separator');
        $this->_commands[] = $command;
        return $command;
    }
     
    /**
     * Add a command
     *
     * @param   string	$command The command name
     * @param	mixed	$config  Parameters to be passed to the command
     * @return  \KControllerToolbarCommand  The command that was added
     */
    public function addCommand($command, $config = array())
    {
        if (!($command instanceof  KControllerToolbarCommand)) {
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
     * @return mixed KControllerToolbarCommand if found, false otherwise.
     */ 
    public function getCommand($name, $config = array()) 
    {
        //Create the config object
        $command = new KControllerToolbarCommand($name, $config);

        //Attach the command to the toolbar
        $command->setToolbar($this);
        
        //Find the command function to call
        if(method_exists($this, '_command'.ucfirst($name)))
        {
            $function =  '_command'.ucfirst($name);
            $this->$function($command);
        }
        else
        {
            //Don't set an action for GET commands
            if(!isset($command->href))
            {
                $command->append(array(
         			'attribs'    => array(
               			'data-action'  => $command->getName()
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
        return new RecursiveArrayIterator($this->getCommands());
    }
 
    /**
     * Reset the commands array
     *
     * @return  \KControllerToolbarAbstract
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
     * @param   string  Method name
     * @param   array   Array containing all the arguments for the original call
     * @return mixed
     * @see addCommand()
     */
    public function __call($method, $args)
    {  
		$parts = KInflector::explode($method);

		if($parts[0] == 'add' && isset($parts[1]))
		{
		    $config = isset($args[0]) ? $args[0] : array();	    
		    $command = $this->addCommand(strtolower($parts[1]), $config);
			return $command;
		}
        
        return parent::__call($method, $args);
    }
}