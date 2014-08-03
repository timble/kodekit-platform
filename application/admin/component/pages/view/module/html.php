<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Module Html View
 *
 * @author   Gergo Erdosi <http://github.com/gergoerdosi>
 * @package  Component\Pages
 */
class PagesViewModuleHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $model  = $this->getModel();
        $module = $model->fetch();

        if ($this->getModel()->getState()->isUnique())
        {
            if ($module->isNew())
            {
                $module->application = $model->application;
                $module->name        = $model->name;
            }

            $path = $this->getObject('manager')->getClassLoader()->getBasepath($module->application);
            JFactory::getLanguage()->load($module->component, 'mod_'.$module->name, $path);
        }

        return parent::_actionRender($context);
    }

    protected function _fetchData(Library\ViewContext $context)
    {
        $module = $this->getModel()->fetch();

        if ($this->getLayout() == 'modal')
        {
            $context->data->menus = $this->getObject('com:pages.model.menus')
                ->sort('title')->fetch();

            $context->data->pages = $this->getObject('com:pages.model.pages')
                ->application('site')->fetch();

            $context->data->modules = $this->getObject('com:pages.model.modules')
                ->application('site')->fetch();
        }

        $context->data->params = $module->getParameters();

        parent::_fetchData($context);
    }
}
