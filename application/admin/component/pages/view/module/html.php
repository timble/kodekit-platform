<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;

/**
 * Module Html View
 *
 * @author   Gergo Erdosi <http://github.com/gergoerdosi>
 * @package  Kodekit\Platform\Pages
 */
class ViewModuleHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $model  = $this->getModel();
        $module = $model->fetch();

        if ($model->getState()->isUnique())
        {
            if ($module->isNew())
            {
                $module->application = $model->application;
                $module->name        = $model->name;
            }
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

    protected function _beforeRender(Library\ViewContext $context)
    {
        $module = $this->getModel()->fetch();

        $package = $module->getIdentifier()->package;
        $domain  = $module->getIdentifier()->domain;

        if($domain) {
            $url = 'com://'.$domain.'/'.$package;
        } else {
            $url = 'com:'.$package;
        }

        $this->getObject('translator')->load($url);
    }
}
