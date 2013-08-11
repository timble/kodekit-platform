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
    public function render()
    {
        // Load languages.
        $language   = JFactory::getLanguage();

        foreach($this->getObject('com:extensions.model.extensions')->getRowset() as $extension) {
            $language->load($extension->name);
        }
        
        // Load components.
        $state = $this->getModel()->getState();
        $page  = $this->getModel()->getRow();

        $menu  = $this->getObject('com:pages.model.menus')
            ->id($state->menu)
            ->getRow();
        
        $this->extensions = $this->getObject('com:pages.model.types')
            ->application($menu->application)
            ->getRowset();

        // Get available and assigned modules.
        $available = $this->getObject('com:pages.model.modules')
            ->published(true)
            ->application('site')
            ->getRowset();

        $query = $this->getObject('lib:database.query.select')
            ->where('tbl.pages_page_id IN :id')
            ->bind(array('id' => array((int) $page->id, 0)));

        $assigned = $this->getObject('com:pages.database.table.modules_pages')
            ->select($query);

        $this->modules = (object) array('available' => $available, 'assigned' => $assigned);

        // Assign menu.
        $this->menu = $this->getObject('com:pages.model.menus')->id($state->menu)->getRow();

        // Assign parent ID
        $this->parent_id = $page->getParentId();

        return parent::render();
    }
}
