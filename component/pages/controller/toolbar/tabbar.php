<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Tabbar Controller Toolbar
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ControllerToolbarTabbar extends Library\ControllerToolbarAbstract
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

        if($controller->isEditable() && !$controller->getView()->isCollection()) {
            $command->disabled = true;
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
        $menu = $this->getObject('pages.menus')->find(array('slug' => 'menubar'));

        if(count($menu) && $this->getObject('manager')->isRegistered('dispatcher'))
        {
            $package    = $this->getObject('dispatcher')->getIdentifier()->package;
            $view       = $this->getObject('request')->query->get('view', 'cmd');
            $groups     = $this->getObject('user')->getGroups();
            $translator = $this->getObject('translator');

            // Make sure that pages without an assigned group are also included.
            $groups[] = 0;

            $pages = $this->getObject('pages')->find(array(
                'pages_menu_id'     => $menu->id,
                'component'         => $package,
                'hidden'            => 0,
                'users_group_id'    => $groups
            ));

            foreach($pages as $page)
            {
                if($page->level > 2)
                {
                    $this->addCommand($translator($page->title), array(
                        'href'   => (string) $page->link_url,
                        'active' => (string) $view == $page->getLink()->query['view']
                    ));
                }
            }
        }

        return parent::getCommands();
    }
}