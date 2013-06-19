<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Template Menubar Helper
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationTemplateHelperMenubar extends Pages\TemplateHelperList
{
 	/**
     * Render the menubar
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'attribs' => array('class' => array())
        ));

        $groups   = $this->getObject('user')->getGroups();

        // Make sure that pages without an assigned group are also included.
        $groups[] = 0;

        $result = '';

        $menus = $this->getObject('com:pages.model.menus')
            ->application('admin')
            ->getRowset();

        $menu = $menus->find(array('slug' => 'menubar'));

        if(count($menu))
        {
            $pages  = $this->getObject('application.pages')->find(array('pages_menu_id' => $menu->top()->id, 'hidden' => 0, 'users_group_id' => $groups));
            $result = $this->pages(array('pages' => $pages, 'attribs' => $config->attribs));
        }

        return $result;
    }
}