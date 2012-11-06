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
        if($this->getLayout() == 'modal')
        {
            $this->menu      = $this->getService('com://admin/pages.model.menus')->sort('title')->getList();
            $this->pages     = $this->getService('com://admin/pages.model.pages')->getList();
            $this->modules   = $this->getService('com://admin/extensions.model.modules')->application('site')->getList();
            $this->relations = $this->getService($this->getModel()->getIdentifier())->module($this->getModel()->module)->getList();
        }

        if($this->getLayout() == 'form')
        {
            $module = $this->getModel()->getItem();

            $path = $this->getIdentifier()->getApplication($module->application);
            JFactory::getLanguage()->load($module->getIdentifier()->package, $module->name, $path);
        }

        return parent::display();
    }
}
