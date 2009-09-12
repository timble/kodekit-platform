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
		$app 		= $this->_identifier->application;
		$package 	= $this->_identifier->package;
		$name 		= $this->_identifier->name;
		
		if(KInflector::isPlural($name))
		{
			//Assign the data of the model to the view
			$model = KFactory::get($app.'::com.'.$package.'.model.'.$name);
			$this->assign($name, 	$model->getList())
				 ->assign('total',	$model->getTotal());
		}
		else
		{
			//Assign the data of the model to the view
			$model = KFactory::get($app.'::com.'.$package.'.model.'.KInflector::pluralize($name));
			$this->assign($name, $model->getItem());
		}
		
		//Auto-assign the state to the view
		$this->assign('state', $model->getState());
		
		// Create the toolbar
		$toolbar = KFactory::get($app.'::com.'.$package.'.toolbar.'.$name);
		
		// Render the toolbar
		if($this->_layout == 'form') {
			$this->_document->setBuffer($toolbar->render(), 'modules', 'toolbar');
		}
		
		// Render the title
		$this->_document->setBuffer($toolbar->renderTitle(), 'modules', 'title');
		
		// Display the layout
		parent::display();
	}
}