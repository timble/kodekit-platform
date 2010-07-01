<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default View Controller
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultControllerDefault extends KControllerView
{
	/**
	 * Browse a list of items
	 *
	 * @return void
	 */
	protected function _actionBrowse()
	{
		$model = KFactory::get($this->getModel());
		if($model->getState()->limit === 0) {
			$model->set('limit', KFactory::get('lib.joomla.application')->getCfg('list_limit'));
		}
			
		return parent::_actionBrowse();
	}
}