<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Module Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class PagesViewModuleHtml extends Framework\ViewHtml
{
    public function render()
    {
        $model  = $this->getModel();
        $module = $model->getRow();

        if($this->getLayout() == 'modal')
        {
            $this->menus   = $this->getService('com:pages.model.menus')
                                  ->sort('title')->getRowset();

            $this->pages   = $this->getService('com:pages.model.pages')
                                  ->application('site')->getRowset();

            $this->modules = $this->getService('com:pages.model.modules')
                                  ->application('site')->getRowset();
        }

        if($this->getLayout() == 'form')
        {
            if($module->isNew())
            {
                $module->application = $model->application;
                $module->name        = $model->name;
            }

            $path = $this->getService('loader')->getApplication($module->application);
            JFactory::getLanguage()->load(substr($module->component_name, 4), $module->name, $path);
        }

        return parent::render();
    }
}
