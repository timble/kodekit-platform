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
 * Module Html View
 *
 * @author   Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Component\Pages
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
