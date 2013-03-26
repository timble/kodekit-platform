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
 * Application Tabbar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ApplicationControllerToolbarTabbar extends Framework\ControllerToolbarAbstract
{
	/**
	 * Push the tabbar into the view
	 * .
	 * @param	Framework\Event	A event object
	 */
    public function onBeforeControllerRender(Framework\Event $event)
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
     * @return  Framework\ControllerToolbarCommand
     */
    public function addCommand($name, $config = array())
    {
        $command = parent::addCommand($name, $config);
        
        $controller = $this->getController();
        
        if($controller->isEditable() && Framework\StringInflector::isSingular($controller->getView()->getName())) {
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
        $menu = $this->getService('com:pages.model.menus')
            ->application('admin')
            ->getRowset()
            ->find(array('slug' => 'menubar'));

        if(count($menu))
        {
            $package    = $this->getService('component')->getIdentifier()->package;
            $view       = $this->getService('component')->getController()->getIdentifier()->name;
            $component  = $this->getService('application.components')->getComponent($package);

            $pages     = $this->getService('application.pages')->find(array(
                'pages_menu_id'           => $menu->top()->id,
                'extensions_component_id' => $component->id
            ));

            foreach($pages as $page)
            {
                if($page->level > 2)
                {
                    $this->addCommand(JText::_((string) $page->title), array(
                        'href'   => (string) $page->link_url,
                        'active' => (string) $view == Framework\StringInflector::singularize($page->getLink()->query['view'])
                    ));
                }
            }
        }

	    return parent::getCommands();   
	}
}