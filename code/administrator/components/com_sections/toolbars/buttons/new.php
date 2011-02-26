<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * New Toolbar Button Class
 * 
 * @author		John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 */
class ComSectionsToolbarButtonNew extends KToolbarButtonAbstract
{
	public function getLink()
	{
		$option = KRequest::get('get.option', 'cmd');
		$view	= KInflector::singularize(KRequest::get('get.view', 'cmd'));
		
		return 'index.php?option='.$option.'&view='.$view.'&scope=content';
	}
}
