<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright  	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Application Tabbar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ApplicationControllerToolbarTabbar extends Library\ControllerToolbarAbstract
{
	/**
	 * Push the tabbar into the view
	 * .
	 * @param	Library\Event	A event object
	 */
    public function onBeforeControllerRender(Library\Event $event)
    {
        $event->getTarget()->getView()->tabbar = $this;
    }
 	
 	/**
     * Add a command
     * 
     * Disable the tabbar only for singular views that are editable.
     *
     * @param   string	The command name
     * @param	mixed	Parameters to be passed to the command
     * @return  Library\ControllerToolbarCommand
     */
    public function addCommand($name, $config = array())
    {
        $command = parent::addCommand($name, $config);
        
        $controller = $this->getController();
        
        if($controller->isEditable() && Library\StringInflector::isSingular($controller->getView()->getName())) {
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
        $menu = $this->getObject('com:pages.model.menus')
            ->application('admin')
            ->getRowset()
            ->find(array('slug' => 'menubar'));

        if(count($menu))
        {
            $package    = $this->getObject('component')->getIdentifier()->package;
            $view       = $this->getObject('component')->getController()->getIdentifier()->name;
            $component  = $this->getObject('application.components')->getComponent($package);

            $pages     = $this->getObject('application.pages')->find(array(
                'pages_menu_id'           => $menu->top()->id,
                'extensions_component_id' => $component->id
            ));

            foreach($pages as $page)
            {
                if($page->level > 2)
                {
                    $this->addCommand(JText::_((string) $page->title), array(
                        'href'   => (string) $page->link_url,
                        'active' => (string) $view == Library\StringInflector::singularize($page->getLink()->query['view'])
                    ));
                }
            }
        }

	    return parent::getCommands();   
	}
}