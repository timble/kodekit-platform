<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Menubar Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */

class ApplicationTemplateHelperMenubar extends Pages\TemplateHelperList
{
 	/**
     * Render the menubar
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'attribs' => array('class' => array())
        ));

        $groups = $this->getObject('user')->getGroups();

        // Make sure that pages without an assigned group are also included.
        $groups[] = 0;

        $result = '';

        $menus = $this->getObject('com:pages.model.menus')
            ->application('admin')
            ->fetch();

        $menu = $menus->find(array('slug' => 'menubar'));

        if(count($menu))
        {
            $pages  = $this->getObject('application.pages')->find(array('pages_menu_id' => $menu->id, 'hidden' => 0, 'users_group_id' => $groups));
            $result = $this->pages(array('pages' => $pages, 'attribs' => $config->attribs));
        }

        return $result;
    }
}