<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Dashboard View
 *   
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 */

class ComDashboardViewDashboardHtml extends ComDefaultViewHtml
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'layout' => 'default'
        ));
        
        parent::_initialize($config);
    }
    
    public function display()
	{
		$modules = KFactory::get('com://admin/extensions.model.modules')->position('cpanel')->application('administrator')->enabled(1)->getList();
		$this->assign('modules', $modules);
        
		return parent::display();
	}
}