<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 - 2010 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class ComTermsViewTermsHtml extends KViewHtml
{
	public function display()
	{
		$model = KFactory::get($this->getModel());
		
		//If no row_id exists assign an empty array
		if($model->get('row_id')) {
			$this->assign('disabled', false);
		} else {
			$this->assign('disabled', true);
		}
			
		parent::display();
	}
}
