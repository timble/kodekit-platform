<?php
/**
 * @version   	$Id: menubar.php 4774 2012-08-08 22:13:02Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright  	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license   	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Application Menubar
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationControllerToolbarMenubar extends KControllerToolbarAbstract
{
    public function onBeforeControllerGet(KEvent $event)
    {   
        $event->getTarget()->getView()->menubar = $this;
    }

    public function getCommand($name, $config = array())
    {
        $command = parent::getCommand($name, $config);

        if($this->getService('component')->getController()->getView()->getLayout() == 'form') {
            $command->disabled = true;
        }

        return $command;
    }

    public function getCommands()
	{
        $user = JFactory::getUser();

        $this->addCommand('link', array('label' => 'Dashboard', 'href'  => 'option=com_dashboard&view=dashboard'));

        if($user->authorize('com_menus', 'manage')) {
            $this->addCommand('link', array('label' => 'Pages', 'href'  => 'option=com_pages&view=pages'));
        }

        if($user->authorize('com_components', 'manage'))
        {
            $menu = $this->addCommand('menu', array('label' => 'Content'));
            $menu->addCommand('link', array('label' => 'Articles', 'href'  => 'option=com_articles&view=articles'));
            $menu->addCommand('link', array('label' => 'Web Links', 'href'  => 'option=com_weblinks&view=weblinks'));
            $menu->addCommand('link', array('label' => 'Contacts', 'href'  => 'option=com_contacts&view=contacts'));
            $menu->addCommand('link', array('label' => 'Languages', 'href'  => 'option=com_languages&view=languages'));
        }

        $this->addCommand('link', array('label' => 'Files', 'href'  => 'option=com_files&view=files'));

        if($user->authorize('com_users', 'manage')) {
            $this->addCommand('link', array('label' => 'Users', 'href'  => 'option=com_users&view=users'));
        }

        if($user->authorize('com_settings', 'manage'))
        {
            $menu = $this->addCommand('menu', array('label' => 'Extensions'));
            $menu->addCommand('link', array('label' => 'Settings', 'href'  => 'option=com_extensions&view=settings'));
        }

        if($user->authorize('com_settings', 'manage'))
        {
            $menu = $this->addCommand('menu', array('label' => 'Tools'));
            $menu->addCommand('link', array('label' => 'Activity Logs','href'  => 'option=com_activities&view=activities'));
            $menu->addCommand('link', array('label' => 'Clean Cache', 'href'  => 'option=com_cache&view=items'));
        }
	
	    return parent::getCommands();   
	}
}

