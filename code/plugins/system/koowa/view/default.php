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
		$app 		= $this->identifier->application;
		$component 	= $this->identifier->component;
		$name 		= $this->identifier->name;

		if(KInflector::isPlural($name))
		{
			$model = KFactory::get($app.'::com.'.$component.'.model.'.$name);
			$this->assign($name, 		$model->getList());
			$this->assign('filter',  	$model->getFilters());
			$this->assign('pagination', $model->getPagination());
		}
		else
		{
			$model = KFactory::get($app.'::com.'.$component.'.model.'.KInflector::pluralize($name));
			$this->assign($name, $model->getItem());
		}

		// Display the layout
		parent::display();
	}
}
