<?php
/**
 * @version     $Id: html.php 3031 2011-10-09 14:21:07Z johanjanssens $
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
    public function display()
    {
        // Load languages.
        $language   = JFactory::getLanguage();
        $components = $this->getService('com://admin/extensions.model.components')->getList();
        $admin_path = $this->getIdentifier()->getApplication('admin');

        foreach($components as $component) {
            $language->load(substr($component->name, 4), null, $admin_path);
        }
        
        // Load components.
        $model = $this->getModel();
        $menu  = $this->getService('com://admin/pages.model.menus')
            ->id($model->menu)
            ->getItem();
        
        $components = $this->getService('com://admin/pages.model.types')
            ->application($menu->application)
            ->getList();
        
		$this->assign('components', $components);
		
        // Get available and assigned modules.
        $available = $this->getService('com://admin/pages.model.modules')
            ->published(true)
            ->application('site')
            ->getList();

        $query = $this->getService('koowa:database.query.select')
            ->where('tbl.pages_page_id IN :id')
            ->bind(array('id' => array((int) $model->getItem()->id, 0)));

        $assigned = $this->getService('com://admin/pages.database.table.modules_pages')
            ->select($query);

        $this->assign('modules', (object) array('available' => $available, 'assigned' => $assigned));

        return parent::display();
    }
}
