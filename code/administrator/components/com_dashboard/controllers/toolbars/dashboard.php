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
 * Dashboard Toolbar Class
 *   
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Dashboard
 */
 
class ComDashboardControllerToolbarDashboard extends ComDefaultControllerToolbarDefault
{
	public function __construct(KConfig $config = null)
    {
        parent::__construct($config);
        
		$this->setTitle(JText::_('Dashboard'));
    }
}