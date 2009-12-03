<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TermsViewTermsAjax extends KViewAjax
{
	public function display()
	{
		
		$model = KFactory::get($this->getModel());
		
		//If no row_id exists assign an empty array
		if($model->get('row_id')) 
		{
			$this->assign('disabled', false);
			KViewAbstract::display();
		}  
		else 
		{
			$this->assign('terms'   , array());
			$this->assign('disabled', true);
		}
				
		//Auto-assign the state to the view
		$this->assign('state', $model->getState());
		
		//Load the template
		$template = $this->loadTemplate();
		
		//Render the scripts
		foreach ($this->_document->_scripts as $source => $type) {
			echo '<script type="'.$type.'" src="'.$source.'"></script>'."\n";
		}
	
		echo $template;
	}
}
