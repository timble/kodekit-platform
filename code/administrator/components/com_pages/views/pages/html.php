<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Pages Html View Class
 *   
 * @author    	Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Pages
 */
class ComPagesViewPagesHtml extends ComDefaultViewHtml
{
    public function display()
    {
        $applications = array_keys($this->getIdentifier()->getApplications());
        $this->assign('applications', $applications);
        
        $menus = $this->getService('com://admin/pages.model.menus')->getList();
        $this->assign('menus', $menus);
        
        return parent::display();
    }
}