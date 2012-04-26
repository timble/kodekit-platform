<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Activities
 * @copyright	Copyright (C) 2010 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Activities Listbox Template Helper Class
 *
 * @author Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category Nooku
 * @package Nooku_Components
 * @subpackage Activities
 */

class ComActivitiesTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
	
	public function ips($config = array())
	{
		
		$config = new KConfig($config);
		
		$config->append(array('name' => 'ip'));
		
		return parent::_render($config);
	}

}