<?php
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Page Html View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesViewPageHtml extends ComDefaultViewHtml
{
    public function render()
    {
        // Load languages.
        $language   = JFactory::getLanguage();

        foreach($this->getService('com://admin/extensions.model.components')->getRowset() as $component) {
            $language->load($component->name);
        }
        
        // Load components.
        $model = $this->getModel();
        $menu  = $this->getService('com://admin/pages.model.menus')
            ->id($model->menu)
            ->getRow();
        
        $this->components = $this->getService('com://admin/pages.model.types')
            ->application($menu->application)
            ->getRowset();

        // Get available and assigned modules.
        $available = $this->getService('com://admin/pages.model.modules')
            ->published(true)
            ->application('site')
            ->getRowset();

        $query = $this->getService('koowa:database.query.select')
            ->where('tbl.pages_page_id IN :id')
            ->bind(array('id' => array((int) $model->getRow()->id, 0)));

        $assigned = $this->getService('com://admin/pages.database.table.modules_pages')
            ->select($query);

        $this->modules = (object) array('available' => $available, 'assigned' => $assigned);

        return parent::render();
    }
}
