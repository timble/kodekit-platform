<?php
/**
* @version      $Id$
* @category		Koowa
* @package		Koowa_Toolbar
* @copyright    Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
* @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
*/

/**
 * Abstract Controller Toolbar Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @category    Koowa
 * @package     Koowa_Toolbar
 * @uses        KInflector
 * @uses        KMixinClass
 * @uses        KFactory
 */
abstract class KControllerToolbarAbstract extends KObject implements KControllerToolbarInterface, KObjectIdentifiable
{
    /**
     * The toolbar title
     *
     * @var     string
     */
    protected $_title = '';
    
    /**
     * The toolbar icon
     *
     * @var     string
     */
    protected $_icon = '';
     
    /**
     * Commands in the toolbar
     *
     * @var     array
     */
    protected $_commands = array();

    /**
     * Constructor
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config = null)
    {
        //If no config is passed create it
        if(!isset($config)) $config = new KConfig();
        
        parent::__construct($config);

        // Set the title
        $title = empty($config->title) ? KInflector::humanize($this->getName()) : $config->title;
        $this->setTitle($title);
        
        // Set the icon
        $this->setIcon($config->icon);
        
        // Set the controller
        $this->_controller = $config->controller;
    }

    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'title'       => null,
            'icon'        => 'generic',
            'controller'  => null
        ));
        
        parent::_initialize($config);
    }
    
    /**
     * Get the object identifier
     * 
     * @return  KIdentifier 
     * @see     KObjectIdentifiable
     */
    public function getIdentifier()
    {
        return $this->_identifier;
    }
    
	/**
     * Get the controller object
     * 
     * @return  KIdentifier 
     * @see     KObjectIdentifiable
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
        return $this->_identifier->name;
    }
    
    /**
     * Set the toolbar's title
     *
     * @param   string  Title
     * @return  KToolbarInterface
     */
    public function setTitle($title)
    {
        $this->_title = $title;
        return $this;
    }
    
 	/**
     * Get the toolbar's title
     *
     * @return   string  Title
     */
    public function getTitle()
    {
        return $this->_title;
    }
    
    /**
     * Set the toolbar's icon
     *
     * @param   string  Icon
     * @return  KToolbarInterface
     */
    public function setIcon($icon)
    {
        $this->_icon = $icon;
        return $this;
    }
    
	/**
     * Get the toolbar's icon
     *
     * @return   string  Icon
     */
    public function getIcon()
    {
        return $this->_icon;
    }
    
    /**
     * Get the toolbar buttons
     *
     * @return  array 
     */
    public function getCommands()
    {	
		return $this->_commands;
    }

    /**
     * Append a command
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  KToolbarInterface
     */
    public function append($command, $config = array())
    {
        //Find the command function to call
         if(method_exists($this, '_command'.ucfirst($command))) {
            $function =  '_command'.ucfirst($command);
        } else {
            $function = '_commandAction';
        }
        
        //Create the config object 
        $command = new KControllerToolbarCommand($command, $config);
        
        //Call the command function
        $this->$function($command);
        
        array_push($this->_commands, $command);
        return $this;
    }

    /**
     * Prepend a command
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  KToolbarInterface
     */
    public function prepend($command, $config = array())
    {
        //Find the command function to call
        if(method_exists($this, '_command'.ucfirst($command))) {
            $function =  '_command'.ucfirst($command);
        } else {
            $function = '_commandAction';
        }
        
        //Create the config object 
        $command = new KControllerToolbarCommand($command, $config);
      
        //Call the command function
        $this->$function($command);
        
        array_unshift($this->_commands, $command);
        return $this;
    }
    
    /**
     * Reset the commands array
     *
     * @return  KToolbarInterface
     */
    public function reset()
    {
        $this->_commands = array();
        return $this;
    }
}