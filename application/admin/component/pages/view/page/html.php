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
 * Page Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class PagesViewPageHtml extends Library\ViewHtml
{
    public function render()
    {
        // Load languages.
        $language   = JFactory::getLanguage();

        foreach($this->getObject('com:extensions.model.components')->getRowset() as $component) {
            $language->load($component->name);
        }
        
        // Load components.
        $model = $this->getModel();
        $page  = $model->getRow();

        $menu  = $this->getObject('com:pages.model.menus')
            ->id($model->menu)
            ->getRow();
        
        $this->components = $this->getObject('com:pages.model.types')
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
        $this->menu = $this->getObject('com:pages.model.menus')->id($model->menu)->getRow();

        // Assign parent ID
        $this->parent_id = $page->getParentId();

        return parent::render();
    }
}
