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
	/**
	 * Execute and echo's the views output
 	 *
	 * @return KViewDefault
	 */
	public function display()
	{
		//Get the model
		$model = $this->getModel();
		$name  = $this->_identifier->name;

		if(KInflector::isPlural($name))
		{
			//Assign the data of the model to the view
			$this->assign($name, 	$model->getList())
				 ->assign('total',	$model->getTotal());
		}
		else
		{
			//Assign the data of the model to the view
			$this->assign($name, $model->getItem());
		}

		// Create the toolbar
		$toolbar = $this->getToolbar();

		if($this->_layout == 'form')
		{
			// Render the toolbar
			$this->_document->setBuffer($toolbar->render(), 'modules', 'toolbar');
			// Disable the main menu
			KRequest::set('get.hidemainmenu', 1);
		}

		// Render the title
		$this->_document->setBuffer($toolbar->renderTitle(), 'modules', 'title');

		// Display the layout
		parent::display();
	}

	/**
	 * Get the toolbar with the same identifier
	 *
	 * @return	KToolbarAbstract	A KToolbar object
	 */
	public function getToolbar(array $options = array())
	{
		$identifier			= clone $this->_identifier;
		$identifier->path	= array('toolbar');

		return KFactory::get($identifier, $options);
	}
}