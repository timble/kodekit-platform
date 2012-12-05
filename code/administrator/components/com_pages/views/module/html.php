<?php
/**
 * @version     $Id: listbox.php 3031 2011-10-09 14:21:07Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesViewModuleHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $module = $this->getModel()->getItem();

        if($this->getLayout() == 'modal')
        {
            $menus = $this->getService('com://admin/pages.model.menus')->sort('title')->getList();
            $this->assign('menus', $menus);

            $pages = $this->getService('com://admin/pages.model.pages')->application('site')->getList();
            $this->assign('pages', $pages);

            $modules = $this->getService('com://admin/pages.model.modules')->application('site')->getList();
            $this->assign('modules', $modules);

            $relations = $this->getService('com://admin/pages.model.modules_pages')->modules_module_id($module->id)->getList();
            $this->assign('relations', $relations);
        }

        if($this->getLayout() == 'form')
        {
            $path = $this->getIdentifier()->getApplication($module->application);
            JFactory::getLanguage()->load($module->getIdentifier()->package, $module->name, $path);
        }

        return parent::display();
    }
}
