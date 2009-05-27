<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Default View Class
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 */
class KViewDefault extends KViewHtml
{
	public function display()
	{
		$prefix = $this->getClassName('prefix');
		$suffix = $this->getClassName('suffix');
		$model = KFactory::get('admin::com.'.$prefix.'.model.'.$suffix);

		if(KInflector::isPlural($suffix)) {
			$this->assign($suffix, 		$model->getList());
			$this->assign('filter',  	$model->getFilters());
			$this->assign('pagination', $model->getPagination());
		} else {
			$this->assign($suffix, $model->getItem());
		}

		// Display the layout
		parent::display();
	}
}
