<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_View
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPL <http://www.gnu.org/licenses/gpl.html>
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
		$app 		= $this->_identifier->application;
		$package 	= $this->_identifier->package;
		$name 		= $this->_identifier->name;

		if(KInflector::isPlural($name))
		{
			$model = KFactory::get($app.'::com.'.$package.'.model.'.$name);
			$this->assign($name, 	$model->getList())
				 ->assign('total',	$model->getTotal());
		}
		else
		{
			$model = KFactory::get($app.'::com.'.$package.'.model.'.KInflector::pluralize($name));
			$this->assign($name, $model->getItem());
		}

		$this->assign('state', $model->getState());

		// Display the layout
		parent::display();
	}
}
