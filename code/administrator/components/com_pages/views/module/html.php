<?php
/**
 * @version     $Id: listbox.php 3031 2011-10-09 14:21:07Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Module Html View Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */

class ComPagesViewModuleHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));

        parent::_initialize($config);
    }

    public function display()
    {
        $menus = $this->getService('com://admin/pages.model.menus')
            ->sort('title')
            ->getList();
        
        $this->assign('menus', $menus);
        
        $pages = $this->getService('com://admin/pages.model.pages')->getList();
        $this->assign('pages', $pages);

        $modules = $this->getService('com://admin/extensions.model.modules')
            ->application('site')
            ->getList();

        $this->assign('modules', $modules);

        $relations = $this->getService($this->getModel()->getIdentifier())
            ->module($this->getModel()->module)
            ->getList();

        $this->assign('relations', $relations);

        return parent::display();
    }
}
