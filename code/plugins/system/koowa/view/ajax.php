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
 * Ajax View Class
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_View
 */
class KViewAjax extends KViewHtml
{
	/**
	 * Renders and echo's the views output
 	 *
	 * @return KViewAjax
	 */
	public function display()
	{
		$model = KFactory::get($this->getModel());
		
		//Auto-assign the state to the view
		$this->assign('state', $model->getState());
		
		//Get the view name
		$name  = $this->getName();
		
		//Assign the data of the model to the view
		if(KInflector::isPlural($name))
		{
			$this->assign($name, 	$model->getList())
				 ->assign('total',	$model->getTotal());
		}
		else
		{
			$this->assign($name, $model->getItem());
		}
		
		//Load the template
		$template = $this->loadTemplate();
		
		//Render the scripts
		foreach ($this->_document->_scripts as $source => $type) {
			echo '<script type="'.$type.'" src="'.$source.'"></script>'."\n";
		}
	
		//Render the template
		echo $template;
		
		return $this;
	}
}
