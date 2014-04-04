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
 * Page Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Pages
 */
class PagesViewPageHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        // Load languages.
        $translator = $this->getObject('translator');

        foreach($context->data->components as $component) {
            $translator->import($component->name);
        }

        return parent::_actionRender($context);
    }

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
        $context->data->parent_id = $page->getParentId();

        parent::_fetchData($context);
    }
}
