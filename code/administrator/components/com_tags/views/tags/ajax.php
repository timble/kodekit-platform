<?php
/**
 * @version		$Id$
 * @package		Tags
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class TagsViewTagsAjax extends KViewAjax
{
	public function display()
	{
		parent::display();
			
		if(!$id = $this->getModel()->get('row_id')) {
			echo JText::_("Please click 'Apply' to add tags for this profile");
			return;
		}
		
		$this->assign('format', 'ajax');
		return $this;
	}
}
