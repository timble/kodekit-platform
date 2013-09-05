<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Tabbar Controller Toolbar
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationControllerToolbarTabbar extends Library\ControllerToolbarAbstract
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config Configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'type'  => 'tabbar',
        ));

        parent::_initialize($config);
    }
 	
 	/**
     * Add a command
     * 
     * Disable the tabbar only for singular views that are editable.
     *
     * @param   string	$name The command name
     * @param	mixed	$config Parameters to be passed to the command
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
            $extension  = $this->getObject('application.extensions')->getExtension($package);
            $groups     = $this->getObject('user')->getGroups();

            // Make sure that pages without an assigned group are also included.
            $groups[] = 0;

            $pages     = $this->getObject('application.pages')->find(array(
                'pages_menu_id'           => $menu->top()->id,
                'extensions_extension_id' => $extension->id,
                'hidden'                  => 0,
                'users_group_id'          => $groups
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