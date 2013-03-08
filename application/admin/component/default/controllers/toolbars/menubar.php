<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright  	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default Menubar 
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultControllerToolbarMenubar extends Framework\ControllerToolbarAbstract
{
	/**
	 * Push the menubar into the view
	 * .
	 * @param	Framework\Event	A event object
	 */
    public function onBeforeControllerRender(Framework\Event $event)
    {   
        $event->getTarget()->getView()->menubar = $this;
    }
 	
 	/**
     * Add a command
     * 
     * Disable the menubar only for singular views that are editable.
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  Framework\ControllerToolbarCommand
     */
    public function addCommand($name, $config = array())
    {
        $command = parent::addCommand($name, $config);
        
        $controller = $this->getController();
        
        if($controller->isEditable() && Framework\Inflector::isSingular($controller->getView()->getName())) {
            $command->disabled = true;
        }
        
        return $command;
    }

    /**
	 * Get the list of commands
	 *
	 * Will attempt to use information from the xml manifest if possible
	 *
	 * @return  array
	 */
	public function getCommands()
	{
	    $name     = $this->getController()->getIdentifier()->name;
	    $package  = $this->getIdentifier()->package;
        $application = $this->getIdentifier()->namespace;

        $path  = $this->getIdentifier()->getNamespace($application);
	    $path .= '/component/'.$package.'/manifest.xml';

	    if(file_exists($path))
	    {
	        $xml = simplexml_load_file($path);
	        
	        if(isset($xml->admin->menubar))
	        {
	            foreach($xml->admin->menubar->children() as $command)
	            {
	                parse_str($command['href'], $href);
	                if(!isset($command['active'])) {
	                    $command['active'] = ($name == Framework\Inflector::singularize($href['view']));
	                }

	                $this->addCommand(JText::_((string)$command), array(
	            		'href'   => (string) $command['href'],
	            		'active' => (string) $command['active']
	                ));
	            }
	        }
	    }
	
	    return parent::getCommands();   
	}
}