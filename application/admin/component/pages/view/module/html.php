<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Module Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class PagesViewModuleHtml extends Library\ViewHtml
{
    public function render()
    {
        $model  = $this->getModel();
        $module = $model->getRow();

        if($this->getLayout() == 'modal')
        {
            $this->menus   = $this->getObject('com:pages.model.menus')
                                  ->sort('title')->getRowset();

            $this->pages   = $this->getObject('com:pages.model.pages')
                                  ->application('site')->getRowset();

            $this->modules = $this->getObject('com:pages.model.modules')
                                  ->application('site')->getRowset();
        }

        if($this->getModel()->getState()->isUnique())
        {
            if($module->isNew())
            {
                $module->application = $model->application;
                $module->name        = $model->name;
            }

            $path = Library\ClassLoader::getInstance()->getApplication($module->application);
            JFactory::getLanguage()->load(substr($module->extension_name, 4), $module->name, $path);
        }

        // Build path to module config file
        $path  = Library\ClassLoader::getInstance()->getApplication('site');
        $path .= '/component/'.substr($module->extension_name, 4).'/module/'.substr($module->name, 4).'/config.xml';

        $params = new \JParameter( null, $path );
        $params->loadArray($module->params->toArray());

        $this->params = $params;

        return parent::render();
    }
}
