<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;

/**
 * Page Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
 */
class ViewPageHtml extends Library\ViewHtml
{
    protected function _fetchData(Library\ViewContext $context)
    {
        // Load components.
        $state = $this->getModel()->getState();
        $page  = $this->getModel()->fetch();


        $menu  = $this->getObject('com:pages.model.menus')
            ->id($state->menu)
            ->fetch();

        $context->data->components = $this->getObject('com:pages.model.types')
            ->application($menu->application)
            ->fetch();

        // Get available and assigned modules.
        $available = $this->getObject('com:pages.model.modules')
            ->published(true)
            ->application('site')
            ->fetch();

        $query = $this->getObject('lib:database.query.select')
            ->where('tbl.pages_page_id IN :id')
            ->bind(array('id' => array((int) $page->id, 0)));

        $assigned = $this->getObject('com:pages.database.table.modules_pages')
            ->select($query);

        //Assign the modules
        $context->data->modules = (object) array('available' => $available, 'assigned' => $assigned);

        // Assign menu.
        $context->data->menu = $this->getObject('com:pages.model.menus')->id($state->menu)->fetch();

        // Assign parent ID
        $context->data->parent_id = $page->parent_id;

        parent::_fetchData($context);
    }

    protected function _actionRender(Library\ViewContext $context)
    {
        // Load languages.
        $translator = $this->getObject('translator');

        foreach($context->data->components as $component) {
            $translator->load('com:'.$component->name);
        }

        return parent::_actionRender($context);
    }
}
