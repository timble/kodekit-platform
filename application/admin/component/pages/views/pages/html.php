<?php
/**
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
    public function render()
    {
        $this->applications = array_keys($this->getIdentifier()->getApplications());
        $this->menus        = $this->getService('com://admin/pages.model.menus')->getRowset();
        
        return parent::render();
    }
}