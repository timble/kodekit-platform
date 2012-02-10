<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 - 2012 Nooku. All rights reserved.
 * @license 	GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

class ComTermsViewTermsHtml extends ComTermsViewHtml
{
	public function display()
	{
		//If no row exists assign an empty array
		if($this->getModel()->get('row')) {
			$this->assign('disabled', false);
		} else {
			$this->assign('disabled', true);
		}
			
		return parent::display();
	}
}
