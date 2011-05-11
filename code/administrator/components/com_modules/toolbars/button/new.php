<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Next Toolbar Button Class
 * 
 * @author    	Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 */

class ComModulesToolbarButtonNew extends ComDefaultToolbarButtonNew
{
	public function getLink()
	{
		$option = KRequest::get('get.option', 'cmd');
		$view	= KInflector::singularize(KRequest::get('get.view', 'cmd'));
		$client	= KRequest::get('get.client', 'int', 0);
		
		return 'index.php?option='.$option.'&view='.$view.'&client='.$client.'&layout=list';
	}
}