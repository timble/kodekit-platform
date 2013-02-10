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
        $model  = $this->getModel();
        $module = $model->getRow();

        if($this->getLayout() == 'modal')
        {
            $this->menu      = $this->getService('com://admin/pages.model.menus')
                                    ->sort('title')->getRowset();

            $this->pages     = $this->getService('com://admin/pages.model.pages')
                                    ->application('site')->getRowset();

            $this->modules   = $this->getService('com://admin/extensions.model.modules')
                                    ->application('site')->getRowset();

            $this->relations = $this->getService('com://admin/pages.model.modules_pages')
                                    ->modules_module_id($module->id)->getRowset();
        }

        if($this->getLayout() == 'form')
        {
            if($module->isNew())
            {
                $module->application = $model->application;
                $module->name = $model->name;
            }

            $path = $this->getIdentifier()->getApplication($module->application);
            JFactory::getLanguage()->load(substr($module->component_name, 4), $module->name, $path);
        }

        return parent::display();
    }
}
